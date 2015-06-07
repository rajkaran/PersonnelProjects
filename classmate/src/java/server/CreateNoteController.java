/*
 * CreateNoteController.java
 *  controller for the creating note
 *  
 * Revision History
 *  Rajkaran 2013.11.3; Created  
 *  Rajkaran 2013.11.13; Modified to facilitate auto save    
 */

package server;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import model.CustomDataType;
import model.NoteModel;

@WebServlet(name = "CreateNoteController", urlPatterns = {"/createNote"})
public class CreateNoteController extends HttpServlet {

    //Call doPost method
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doPost(request, response);
        
    }

    //Call doPost method
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }
    
    //Creating a new note
    private String[] create(String title, String content){
         //local variable
        CustomDataType returnedData;
        String[] resultArray = new String[]{"true", "", ""};
        
        //instantiate NoteModel class and establish connetion with database
        NoteModel createNote = new NoteModel();
        String connectionResult = createNote.establishConnection();
        
        //Check whether database has connected
        if( "true".equals(connectionResult)){
            returnedData = createNote.createNote(title, content);
            //Check whether database query ran successfully
            if( returnedData.getIsExecutedSuccesfully() == true){
                try {
                    if (returnedData.getRetrievedResult().next())
                        resultArray[2] = Integer.toString( returnedData.getRetrievedResult().getInt(1) );
                    
                    //response.sendRedirect("http://localhost:8080/ClassMate/?i="+insertedId);
                } catch (SQLException ex) {
                    //request.setAttribute("error", "Error: "+ex);
                    resultArray[0] = "false";
                    resultArray[1] = "Error: "+ex;
                }
            }
            else{
                resultArray[0] = "false";
                resultArray[1] = returnedData.getErrorStatement();
                //request.setAttribute("error", returnedData.getErrorStatement());
            }
                
        }
        else{
            resultArray[0] = "false";
            resultArray[1] = connectionResult;
            //request.setAttribute("error", connectionResult);
        }
        
        return resultArray;
    }
    
    //When note is already saved then update it 
    private String[] update(int id, String title, String content){
         //local variable
        CustomDataType returnedData;
        String[] resultArray = new String[]{"true", "", Integer.toString(id)};
        
        //instantiate NoteModel class and establish connetion with database
        NoteModel createNote = new NoteModel();
        String connectionResult = createNote.establishConnection();
        
        //Check whether database has connected
        if( "true".equals(connectionResult)){
            returnedData = createNote.updateNote(id, title, content);
            //Check whether database query ran successfully
            if( returnedData.getIsExecutedSuccesfully() != true){
                resultArray[0] = "false";
                resultArray[1] = returnedData.getErrorStatement();
                //request.setAttribute("error", returnedData.getErrorStatement());
            }   
        }
        else{
            resultArray[0] = "false";
            resultArray[1] = connectionResult;
            //request.setAttribute("error", connectionResult);
        }
        
        return resultArray;
    }
    
    //Save note to the database through AJAX
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        //posted data
        String title = request.getParameter("title");
        String content = request.getParameter("content");
        String id = request.getParameter("id");
        String[] returningInfo = new String[3];
        
        if(id == null){
            returningInfo = create(title, content);
        }
        else 
            returningInfo = update(Integer.parseInt(id), title, content);        
              
        response.setContentType("text");  
        response.setCharacterEncoding("UTF-8"); 
        String emailString = "{ \"response\": \""+returningInfo[0]+"\",\"errorMessage\": \""+returningInfo[1]+"\",\"id\": \""+returningInfo[2]+"\" }";
        response.getWriter().write(emailString);
        
     }

    // Give servlet description
    @Override
    public String getServletInfo() {
        return "controller for the creating note";
    }

}
