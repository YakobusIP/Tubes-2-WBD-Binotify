package binotify.soap.services;

import binotify.soap.database.Database;

import javax.annotation.Resource;
import javax.jws.WebService;
import javax.jws.WebParam;

import java.io.OutputStreamWriter;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.net.InetSocketAddress;
import javax.xml.ws.WebServiceContext;
import javax.xml.ws.handler.MessageContext;
import com.sun.net.httpserver.HttpExchange;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

import org.jooq.impl.DSL;
import org.json.JSONObject;

@WebService(endpointInterface = "binotify.soap.services.ISubscriptionService")
public class SubscriptionServiceImpl implements ISubscriptionService {
    @Resource 
    private WebServiceContext wsContext;
    private static String REST_API_KEY = "RV-260203";
    private static String BAPP_API_KEY = "HC-250902";

    private boolean logRequest(HttpExchange req, String soapFunc, String keyValidate, String desc) {
        try {
            InetSocketAddress remoteAddress = req.getRemoteAddress();
            String ipAddress = remoteAddress.getAddress().getHostAddress();
            String endpoint = "/subscription" + soapFunc; 
            String apiKey = "";

            if (req.getRequestHeaders().get("API-Key") == null) {
                apiKey = "null";
            } else {
                apiKey = req.getRequestHeaders().get("API-Key").get(0);
            }

            // optional output
            System.out.println("=================");
            System.out.println("IP: " + ipAddress);
            System.out.println("Endpoint: " + endpoint);
            System.out.println("API-Key: " + apiKey);
            System.out.println("Key-Validate: " + keyValidate);
            System.out.println("Description: " + desc);
            System.out.println("=================");
            
            // Connect into database
            System.out.println("[ DB LOGGING ]");
            Database loghandler = new Database();
            Connection conn = loghandler.getConnection();
            String query = "INSERT INTO logging (description, ip, endpoint) VALUES (?, ?, ?)";
            
            PreparedStatement ps = conn.prepareStatement(query);
            if (apiKey.equals(keyValidate)) {
                ps.setString(1, desc);
            } else {
                ps.setString(1, "Invalid API-Key: " + desc);
            }
            ps.setString(2, ipAddress);
            ps.setString(3, endpoint);

            if (ps.executeUpdate() == 1) {
                ps.close();
                conn.close();
                System.out.println("[ REQUEST LOGGED ]");
                return apiKey.equals(keyValidate);
            } 
            return false;
        } catch (Exception e) {
            System.out.println("Logging failed!");
            System.out.println(e);
        } 
        return false;
    }

    private String callbackUpdateRequest(int creator_id, String subscriber_id, String status) {
        try {
            String returnResult = "";
            URL url = new URL("http://bnmo-php-web/update-subs");
            //String postData = "creator_id="+creator_id+"&subscriber_id="+subscriber_id+"&status="+status+"";

            JSONObject data = new JSONObject();
            data.put("creator_id", creator_id);
            data.put("subscriber_id", subscriber_id);
            data.put("status", status);
 
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("POST");
            conn.setDoOutput(true);
            conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
            conn.setRequestProperty("Content-Length", Integer.toString(data.length()));
            conn.setRequestProperty("API-Key", BAPP_API_KEY);
            conn.setUseCaches(false);
 
//            try (DataOutputStream dos = new DataOutputStream(conn.getOutputStream())) {
//                dos.writeBytes(postData);
//            }

            OutputStreamWriter wr = new OutputStreamWriter(conn.getOutputStream());
            wr.write(data.toString());
            
            try (BufferedReader br = new BufferedReader(new InputStreamReader(
                conn.getInputStream())))
            {
                String line;
                while ((line = br.readLine()) != null) {
                    returnResult += line;
                }
            }
            return returnResult;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return "Callback failed";
    }

    @Override
    public String getSubscriptionRequests() {
        try {            
            MessageContext msgx = this.wsContext.getMessageContext();
            HttpExchange req = (HttpExchange) msgx.get("com.sun.xml.internal.ws.http.exchange");
            if (!this.logRequest(req, "/getSubscriptionRequests", REST_API_KEY, "Trying to get subscription requests...")) {
                return "GET SUBS FAILED!";
            }

            System.out.println("[ DB SUBSCRIPTION ]");
            Database subhandler = new Database();
            Connection conn = subhandler.getConnection();
            String query = "SELECT * FROM subscription";

            PreparedStatement stmt = conn.prepareStatement(query);
            ResultSet result = stmt.executeQuery();
            System.out.println("[ EXECUTED ]");

            JSONObject response = new JSONObject(DSL.using(conn).fetch(result).formatJSON());
            String stringResponse = response.toString();
            result.close();
            stmt.close();
            conn.close();

            return stringResponse;
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Failed in getting subscription requests!");
        } 
        return "";
    }

    @Override
    public boolean postSubscriptionRequests(@WebParam(name = "creator_id") int creator_id, @WebParam(name = "subscriber_id") String subscriber_id){
        try{
            MessageContext msgx = this.wsContext.getMessageContext();
            HttpExchange req = (HttpExchange) msgx.get("com.sun.xml.internal.ws.http.exchange");
            if (!this.logRequest(req, "/postSubscriptionRequests", BAPP_API_KEY, "Inserting request to SOAP DB from BAPP...")) {
                return false;
            }

            Database subhandler = new Database();
            Connection con = subhandler.getConnection();
            String query = "INSERT INTO subscription (creator_id, subscriber_id) VALUE (?, ?)";

            PreparedStatement stmt = con.prepareStatement(query);
            stmt.setInt(1, creator_id);
            stmt.setString(2, subscriber_id);

            if(stmt.executeUpdate() == 1){
                stmt.close();
                con.close();
                return true;
            }
        }catch(Exception e){
            return false;
        }
        return false;
    }

    @Override
    public boolean acceptSubscriptionRequest(@WebParam(name = "creator_id") int creator_id, @WebParam(name = "subs_id") String subs_id) {
        try {
            MessageContext msgx = this.wsContext.getMessageContext();
            HttpExchange req = (HttpExchange) msgx.get("com.sun.xml.internal.ws.http.exchange");
            if (!this.logRequest(req, "/acceptSubscriptionRequest", REST_API_KEY, "Accepting subscription request...")) {
                return false;
            }
            
            System.out.println("[ DB SUBSCRIPTION ]");
            Database subhandler = new Database();
            Connection conn = subhandler.getConnection();
            String query = "UPDATE subscription SET status='ACCEPTED' WHERE creator_id=? AND subscriber_id=?";
            
            PreparedStatement stmt = conn.prepareStatement(query);
            stmt.setInt(1, creator_id);
            stmt.setString(2, subs_id);
            System.out.println("[ EXECUTED ]");

            if (stmt.executeUpdate() == 1) {
                String callbackResult = this.callbackUpdateRequest(creator_id, subs_id, "ACCEPTED");
                System.out.println("[ CALLBACK ]");
                System.out.println(callbackResult);
                System.out.println("[ CALLBACK ]");
                stmt.close();
                conn.close();
                return true;
            }
        } catch (Exception e) {
            return false;
        }
        return false;
    }

    @Override
    public boolean rejectSubscriptionRequest(@WebParam(name = "creator_id") int creator_id, @WebParam(name = "subs_id") String subs_id) {
        try {
            MessageContext msgx = this.wsContext.getMessageContext();
            HttpExchange req = (HttpExchange) msgx.get("com.sun.xml.internal.ws.http.exchange");
            if (!this.logRequest(req, "/rejectSubscriptionRequest", REST_API_KEY, "Rejecting subscription request...")) {
                return false;
            }

            System.out.println("[ DB SUBSCRIPTION ]");
            Database subhandler = new Database();
            Connection conn = subhandler.getConnection();
            String query = "UPDATE subscription SET status='REJECTED' WHERE creator_id=? AND subscriber_id=?";
            
            PreparedStatement stmt = conn.prepareStatement(query);
            stmt.setInt(1, creator_id);
            stmt.setString(2, subs_id);
            System.out.println("[ EXECUTED ]");

            if (stmt.executeUpdate() == 1) {
                String callbackResult = this.callbackUpdateRequest(creator_id, subs_id, "REJECTED");
                System.out.println("[ CALLBACK ]");
                System.out.println(callbackResult);
                System.out.println("[ CALLBACK ]");
                stmt.close();
                conn.close();
                return true;
            }
        } catch (Exception e) {
            return false;
        }
        return false;
    }

    @Override
    public boolean validateSubscription(@WebParam(name = "creator_id") int creator_id, @WebParam(name = "subscriber_id") String subscriber_id) {
        try {
            MessageContext msgx = this.wsContext.getMessageContext();
            HttpExchange req = (HttpExchange) msgx.get("com.sun.xml.internal.ws.http.exchange");
            if (!this.logRequest(req, "/validateSubscription", REST_API_KEY, "Trying to validate subscription...")) {
                return false;
            }
            
            System.out.println("[ DB SUBSCRIPTION ]");
            Database subhandler = new Database();
            Connection conn = subhandler.getConnection();
            String query = "SELECT * FROM subscription WHERE creator_id = ? AND subscriber_id= ?";
            
            PreparedStatement stmt = conn.prepareStatement(query);
            stmt.setInt(1, creator_id);
            stmt.setString(2, subscriber_id);
            ResultSet result = stmt.executeQuery();
            System.out.println("[ EXECUTED ]");
            
            if(result.next()) {
                String status = result.getString("status");
                result.close();
                stmt.close();
                conn.close();
                return status.equals("ACCEPTED");
            }
            result.close();
            stmt.close();
            conn.close();
            return false;
        } catch (Exception e) {
            System.out.println("Failed in validating subscription requests!" + e.getMessage());
        }
        return false;
    }

    @Override
    public String getSubscriptionRequestsByID(@WebParam(name = "subscriber_id") String subscriber_id) {
        try {
            MessageContext msgx = this.wsContext.getMessageContext();
            HttpExchange req = (HttpExchange) msgx.get("com.sun.xml.internal.ws.http.exchange");
            if (!this.logRequest(req, "/getSubscriptionRequestsByID", BAPP_API_KEY, "Trying to get subscription requests specify subscriber ID...")) {
                return "GET SUBS BY ID FAILED!";
            }

            System.out.println("[ DB SUBSCRIPTION ]");
            Database subhandler = new Database();
            Connection conn = subhandler.getConnection();
            String query = "SELECT * FROM subscription WHERE subscriber_id = ?";
            
            PreparedStatement stmt = conn.prepareStatement(query);
            stmt.setString(1, subscriber_id);
            ResultSet result = stmt.executeQuery();
            System.out.println("[ EXECUTED ]");

            JSONObject response = new JSONObject(DSL.using(conn).fetch(result).formatJSON());
            String stringResponse = response.toString();
            result.close();
            stmt.close();
            conn.close();

            return stringResponse;
        } catch (Exception e) {
            System.out.println("Failed in getting subscription requests by ID " + e);
        } 
        return "";
    }
}
