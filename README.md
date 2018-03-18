# appoindar
Appoindar is a appointment management system or you can use  it any kind of schedule manage purpose.

### Installation
First create a database and import the sql/appoindar.sql file.
Change the database credentials in classes/databaseClass.php then run.

### How it works 
Based on the project period and current time the view is generated. There are three different views:
- Month View
- Week View
- Day View

There are two different mode of view:
- Edit or Admin view
- Public view

At the very beginning of the setup we have to create:
 - the administrator username and password
 - the conflict event checker
 - the periods with duration
 - the activity types
 - the project duration
  
### How to use

##### Event Create
At edit mode just click on the blank space of the date you want to create event. A pop-up will be shown. Fill out  the necessary information and Click submit.
##### Event Update
Click on the event. A pop-up will be  shown with pre-filled  information. Edit then Update. You can drag and drop an event to modify it's date or period.
##### Event Delete
Click on the event then click the Remove the event button.

 ### Credits
 1. Jquery
 2. Bootstrap
 3. Fullcalendar
 4. Ruhama IT Solutions
