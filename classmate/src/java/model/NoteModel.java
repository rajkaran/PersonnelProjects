/*
 * NoteModel.java
 *  Model to save and read note 
 *  
 * Revision History
 *  Rajkaran 2013.11.3; Created  
 *  Rajkaran 2013.11.13; Modify create note method   
 *  Rajkaran 2013.11.14; Modify update note method 
 *  Rajkaran 2013.11.20; Modify  the return value of create and return method 
 */

package model;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import org.apache.commons.lang3.StringEscapeUtils;

public class NoteModel extends DBConnect {
    
    //Class data fields
    private Statement st;
    private ResultSet rs;
    private CustomDataType returningData;
    
    //Constructor
    public NoteModel(){
       
    }
    
    //Insert a record for new note into database
    public CustomDataType createNote(String title, String content){
        st = getSt();
        returningData = new CustomDataType();
        //int insertedId = -1;
        try{
            String query = "INSERT INTO note(title, content) VALUES('"+title+"','"+StringEscapeUtils.escapeHtml4(content)+"')";
            st.executeUpdate(query);
            rs = st.executeQuery("SELECT LAST_INSERT_ID()");
            
            returningData.setIsExecutedSuccesfully(true);
            returningData.setErrorStatement("");
            returningData.setRetrievedResult(rs);
            return returningData;
        }catch(SQLException ex){
            returningData.setIsExecutedSuccesfully(false);
            returningData.setErrorStatement("Error: "+ex);
            returningData.setRetrievedResult(rs);
            return returningData;
        }
    }
    
    //Update a note corresponding to given id
    public CustomDataType updateNote(int id, String title, String content){
        st = getSt();
        returningData = new CustomDataType();
        //int insertedId = -1;
        try{
            String query = "UPDATE note SET title='"+title+"', content='"+StringEscapeUtils.escapeHtml4(content)+"' WHERE id="+id+"";
            st.executeUpdate(query);
            
            returningData.setIsExecutedSuccesfully(true);
            returningData.setErrorStatement("");
            returningData.setRetrievedResult(rs);
            return returningData;
        }catch(SQLException ex){
            returningData.setIsExecutedSuccesfully(false);
            returningData.setErrorStatement("Error: "+ex);
            returningData.setRetrievedResult(rs);
            return returningData;
        }
    }
    
    //Read note corresponding to given id
    public CustomDataType getData(int id){
        st = getSt();
        returningData = new CustomDataType();
        try{
            String query = "select * from note where id ="+id;
            rs = st.executeQuery(query);
            returningData.setIsExecutedSuccesfully(true);
            returningData.setErrorStatement("");
            returningData.setRetrievedResult(rs);
            return returningData;
        }catch(SQLException ex){
            returningData.setIsExecutedSuccesfully(false);
            returningData.setErrorStatement("Error: "+ex);
            returningData.setRetrievedResult(rs);
            return returningData;
        }
    }
    
    
}
