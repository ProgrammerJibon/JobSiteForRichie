<?php
/**
 * 
 * 
 * 
 * 
 * 
 *
 * 
 * 
 * 
 * 
 * 


    first of all, read my project proposal file, and understand what I'll need.
    my backend is PHP and database is MySQLi, in front end, there's HTML, CSS, JS.
    then generate files in php for my project one by one. and at the end, generate a CSS and a JS file containing similiar functionality class and functions.
    the php file should contain the design with stylish UI/UX for the prompt i'll tell you now for each php page.
    the php file will contain both of the PHP code and HTML code. and also, generate a sql code which will help me to create all table in database with just copy and paste the sql code. 
    there will be two kind of users, one is employee and other one is job seeker. 
    and in database, add a extra table for storing cookies and maintain expiry date, then allow users to stay signed in with cookies.


    common files: 


        header.php:
            this is the header with the links, where navigation will be shown as per user type (Employeer/Seeker).
            this file will be used in every page header with require_once("header.php");
            this file will link the css and js file
            this file will contain the starting code of HTML file


        footer.php: 
            this file will contain the footer options with copyrights and end of HTML file

        
        styles.css:
            this files contains the style for classes that are commonly used in pages
        

        script.js:
            this file contains the script of javascript with functions that are commonly used in pages


        functions.php: (MOST IMPORTANT)
            this file will contain the common php functions and will be included in every file top with require_once
            this file will maintain common variables and user data with cookies

        



    before login:


        login.php: 
            this will contain a stylish form where user can login with their email address and password, and below, and link of register.php button.
        

        register.php:
            this will contain a stylish form where user can register with their email address, selecting user type (Employee/Job Seeker), full name, and a password


    after login:


        logout.php:
            this file will clear cookies from browser and update data in cookies table in database
    
    
        post_jobs.php:
            this will contain a form where employee can post jobs, with a job title, salary range, post-position, and job description (with location, requirements, benefits)
        

        dashboard.php
            this will show all the jobs that are posted.
            only title as link(/job_details.php?job_id=JOB_POST_ID) to details of post page, which will be opened in a new tab.
            then in a new line, job description will be shown and use mb_substr to short it in 150 chars, and at the end, add (...)
            then in a new line, post position, and salary range will be shown


        job_details.php:
            this page will show the details of job with job title, salary range, post-position, and job description (with location, requirements, benefits)
            and below, only employers can see all of the applicants who submitted CV, 
            and below, only job seekers will see a form if they didn't apply yet, the form will contain a file input fieled for PDF CV, phone number and expected salary input field. and if he already submitted a application, it'll let him edit details with a click and update his application.
            add the edit button and delete button to job details page



        settings.php: 
            this page will allow users to update his data which he submitted while registration. but every item can be updated separately.


        my_jobs.php:
            this page will show the:
                if employee: my posted jobs
                if seeker: my applied jobs

        edit_job.php: 
            add a page only for empolyeer to update job  details,  
            


        



 

 */