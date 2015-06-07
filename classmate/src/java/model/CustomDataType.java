/*
 * CustomDataType.java
 *  Abstract data type class 
 *  
 * Revision History
 *  Rajkaran 2013.11.20; Created  
 */

package model;

import java.sql.ResultSet;

public class CustomDataType {
    
    //Data type elements
    private Boolean isExecutedSuccesfully;
    private String errorStatement;
    private ResultSet retrievedResult;

    //Constructor
    public CustomDataType(){
       
    }    
    
    //Properties
    public Boolean getIsExecutedSuccesfully() {
        return isExecutedSuccesfully;
    }
    public void setIsExecutedSuccesfully(Boolean isExecutedSuccesfully) {
        this.isExecutedSuccesfully = isExecutedSuccesfully;
    }
    
    public String getErrorStatement() {
        return errorStatement;
    }
    public void setErrorStatement(String errorStatement) {
        this.errorStatement = errorStatement;
    }
    
    public ResultSet getRetrievedResult() {
        return retrievedResult;
    }
    public void setRetrievedResult(ResultSet retrievedResult) {
        this.retrievedResult = retrievedResult;
    }    
    
}
