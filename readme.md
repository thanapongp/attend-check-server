## About AttendCheck

AttendCheck is a class room attendance checking system that utilize many technologies in order to make class room attendance 
checking process as easy as possible.

The project is my senior project.

The system is composed of 3 parts; 
* The server side application.
* The Raspberry Pi that place in every class room.
* [The Mobile application](https://github.com/thanapongp/AttendCheck-android)

## How does it work?

First, the teacher add their course detail into the system through the server side application, the detail includes: course's name, course's schedule, the date when the course starts in that semester as well as the date it ends. 
After that the system will connect to the university's database to fetch the students-that-enrolled-to-that-class's detail.

When the class starts, all the students have to do is open their mobile application, press the "attendance" button in there and viola! the application will try to look for the rPi that get place in the class room and try to send the required data to it, then the rPi will communicate with the server to mark the student as "attended".

## More info

All the detail above is simplify. You can find every single detail, documentation (in Thai), diagram etc. about the system at my [Google Drive folder.](https://drive.google.com/open?id=0B_xvpv4tuIe3LW5wOHhSNktPVzA)
