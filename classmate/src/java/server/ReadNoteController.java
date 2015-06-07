/*
 * ReadNoteController.java
 *  Load a particular note by retrieving it's content from database
 *  
 * Revision History
 *  Rajkaran 2013.11.22; Created
 *  Rajkaran 2013.11.25; Throwing all possible errors
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
//import model.*;

@WebServlet(name = "ReadNoteController", urlPatterns = {"/readNote"})
public class ReadNoteController extends HttpServlet {

    //Retrieve note content from database for the given id
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException, SQLException {
        
        //ResultSet retrievedData;
        //DBConnect connect = new DBConnect();
        NoteModel readNote1 = new NoteModel();
        CustomDataType returnedData;
        
        int noteId = Integer.parseInt(request.getParameter("i"));
        String connectionResult = readNote1.establishConnection();
        
        //Check whether database has connected 
        if( "true".equals(connectionResult)){
            returnedData = readNote1.getData(noteId);
            //Check whether database query ran successfully
            if(returnedData.getIsExecutedSuccesfully() == true)
                //Check whether data found in database
                if( !returnedData.getRetrievedResult().isBeforeFirst() ){
                    request.setAttribute("error", "Note did not found.");                
                 }
                else
                    request.setAttribute("getData", returnedData.getRetrievedResult());
            else
               request.setAttribute("error", returnedData.getErrorStatement()); 
        }
        else{
           request.setAttribute("error", connectionResult); 
        }
        
        String url = "/WEB-INF/ReadNote.jsp";
        RequestDispatcher dispatcher = getServletContext().getRequestDispatcher(url);
        dispatcher.forward(request, response);
        
    }

    // Call processRequest method
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        try {
            processRequest(request, response);
        } catch (SQLException ex) {
            Logger.getLogger(ReadNoteController.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    // Call processRequest method
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        try {
            processRequest(request, response);
        } catch (SQLException ex) {
            Logger.getLogger(ReadNoteController.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    // Give servlet description
    @Override
    public String getServletInfo() {
        return "Load a particular note by retrieving it's content from database";
    }

}
