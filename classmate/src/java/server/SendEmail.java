/*
 * SendEmail.java
 *  Sends note url through email 
 *  
 * Revision History
 *  Rajkaran 2013.11.27; Created
 */

package server;

import java.io.IOException;
import java.util.*;
import javax.mail.*;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;
import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.InputStream;



 
@WebServlet(name = "sendEmail", urlPatterns = {"/sendEmail"})
public class SendEmail extends HttpServlet {

    //Call doPost method
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doPost(request, response);       
    }
    
    //Call doPost method
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        doPost(request, response);
    }

    //Send email to the email id received through post
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        //Create url of note to send to the given email recipient
        String[] emailStatus = new String[2];
        String emailTo = request.getParameter("emailTo").trim();
        String emailMessage = "Please click on below link to navigate to shared note: \n"
                +"http://localhost:8080/ClassMate/?i="
                +request.getParameter("noteId").trim();
                
        //configuring smtp server properties
        Properties props = new Properties();
        props.put("mail.smtp.host", "smtp.gmail.com");
        props.put("mail.smtp.socketFactory.port", "465");
        props.put("mail.smtp.socketFactory.class", "javax.net.ssl.SSLSocketFactory");
        props.put("mail.smtp.auth", "true");
        props.put("mail.smtp.port", "465");

        //Calling local class to authenticate server credentials
        Authenticator auth = new SendEmail.SMTPAuthenticator("k.mittal553@gmail.com", "72552726");
        Session session = Session.getInstance(props, auth);

        //Sending email
        try{
           Message message = new MimeMessage(session);
           message.setFrom( new InternetAddress("k.mittal553@gmail.com") );
           message.setRecipients(Message.RecipientType.TO, InternetAddress.parse(emailTo));
           message.setSubject("ClassMate - shared note");
           message.setText(emailMessage);
           Transport.send(message);
           emailStatus[0] = "sent";
           emailStatus[1] = "<span>Email has been sent successfully.</span>";
        }catch(MessagingException ex){
            emailStatus[0] = "fail";
            emailStatus[1] = "Error: " + ex;
        }
        
        //Return response - success or failure 
        response.setContentType("text");  
        response.setCharacterEncoding("UTF-8"); 
        String emailString = "{ \"response\": \""+emailStatus[0]+"\",\"message\": \""+emailStatus[1]+"\" }";
        response.getWriter().write(emailString);
    }
    
    //Overriding SMTP Authenticator class provided by the java
    private class SMTPAuthenticator extends Authenticator {

        private final PasswordAuthentication authentication;

        public SMTPAuthenticator(String login, String password) {
            authentication = new PasswordAuthentication(login, password);
        }

        @Override
        protected PasswordAuthentication getPasswordAuthentication() {
            return authentication;
        }
    }

    // Give servlet description
    @Override
    public String getServletInfo() {
        return "Sends note url through email";
    }    
    
}

