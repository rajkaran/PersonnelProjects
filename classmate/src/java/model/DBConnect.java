/*
 * DBConnect.java
 *  This class establishes the connection with mysql database
 *  
 * Revision History
 *  Rajkaran 2013.11.3; Created
 */

package model;

import java.sql.*;


public class DBConnect {
    
    //Class datafields
    private Connection con;
    private Statement st;
    private ResultSet rs;
    
    public DBConnect(){
       
    }
    
    //Establish database connection
    public String establishConnection(){
        try{
            Class.forName("com.mysql.jdbc.Driver");
            con = DriverManager.getConnection("jdbc:mysql://localhost:3306/classmate", "root", "");
            st = con.createStatement();
            return "true";
        }catch(Exception ex){
            return "Error:"+ex;
        }
    }

    //Return statement of this instance
    public Statement getSt() {
        return st;
    }   
    
}
