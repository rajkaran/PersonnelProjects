/*
 * DecideTargetPage.java
 *  This is the entry point of the application, this decides whether
 *  user is creating new note or reading an pold one.
 *  
 * Revision History
 *  Rajkaran 2013.11.3; Created
 */

package server;

import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet(name = "DecideTargetPage", urlPatterns = {"/targetPage"})
public class DecideTargetPage extends HttpServlet {

    //Call doGet method
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
      
        doGet(request, response);
    }    

    /* Read the user input url and redirect to create new page or read a note 
    controller*/ 
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        if( request.getParameter("i") == null )       
            request.getRequestDispatcher("/WEB-INF/CreateNote.jsp").forward(request, response);
        else
            request.getRequestDispatcher("/readNote").forward(request, response);
        
        
    }

    //Call doGet method
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doGet(request, response);
    }

    // Give servlet description
    @Override
    public String getServletInfo() {
        return "This is the entry point of the application, this decides whether" 
                    + " user is creating new note or reading an pold one.";
    }

}
