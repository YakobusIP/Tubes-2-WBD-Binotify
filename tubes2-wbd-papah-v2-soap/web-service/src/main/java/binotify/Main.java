package binotify;

import binotify.soap.services.SubscriptionServiceImpl;

import javax.xml.ws.Endpoint;

public class Main {
    public static void main(String[] args) {
        System.out.println("Starting SOAP...");
        Endpoint.publish(
            "http://0.0.0.0:7000/subscription", 
            new SubscriptionServiceImpl()
        );
        System.out.println("Endpoints successfully published!");
    }
}