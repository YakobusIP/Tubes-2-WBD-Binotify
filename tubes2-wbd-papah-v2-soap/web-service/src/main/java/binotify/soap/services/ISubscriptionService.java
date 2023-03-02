package binotify.soap.services;

import javax.jws.WebService;
import javax.jws.WebMethod;
import javax.jws.WebParam;
import javax.jws.soap.SOAPBinding;
import javax.jws.soap.SOAPBinding.Style;

@WebService
@SOAPBinding(style = Style.DOCUMENT)
public interface ISubscriptionService {
    @WebMethod
    public String getSubscriptionRequests();

    @WebMethod
    public boolean postSubscriptionRequests(
        @WebParam(name = "creator_id") int creator_id, 
        @WebParam(name = "subscriber_id") String subscriber_id
    );
    
    @WebMethod
    public boolean acceptSubscriptionRequest(
        @WebParam(name = "creator_id") int creator_id, 
        @WebParam(name = "subs_id") String subs_id
    );

    @WebMethod
    public boolean rejectSubscriptionRequest(
        @WebParam(name = "creator_id") int creator_id, 
        @WebParam(name = "subs_id") String subs_id
    );

    @WebMethod
    public boolean validateSubscription(
        @WebParam(name = "creator_id") int creator_id, 
        @WebParam(name = "subscriber_id") String subscriber_id
    );

    @WebMethod
    public String getSubscriptionRequestsByID(
        @WebParam(name = "subscriber_id") String subscriber_id
    );
}
