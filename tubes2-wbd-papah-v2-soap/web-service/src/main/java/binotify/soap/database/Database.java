package binotify.soap.database;

import java.sql.*;

public class Database {
    private Connection connection;
    private static String DB_URL = "jdbc:mysql://bnmo-soap-db:3306/bnmo_premium";
    private static String DB_USERNAME = "bnmo";
    private static String DB_PASSWORD = "bnmo";

    public Database() {
        try {
            System.out.println("Connecting to MYSQL Database...");
            this.connection = DriverManager.getConnection(DB_URL, DB_USERNAME, DB_PASSWORD);
            System.out.println("Database connected!");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Failed to connect to database");
        }
    }

    public Connection getConnection() {
        return this.connection;
    }
}
