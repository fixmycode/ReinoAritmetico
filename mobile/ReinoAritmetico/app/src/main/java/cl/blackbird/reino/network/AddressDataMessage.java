package cl.blackbird.reino.network;

/**
 * Created by carlos on 26/7/2014.
 */
public class AddressDataMessage {
    private String route, name, classroom, school, ANDROID_ID;

    public AddressDataMessage(String route, String name, String classroom, String school, String ANDROID_ID){
        this.route = route;
        this.name = name;
        this.classroom = classroom;
        this.school = school;
        this.ANDROID_ID = ANDROID_ID;
    }

    public String getRoute(){
        return route;
    }

    public String getName(){
        return name;
    }

    public String getClassroom(){
        return classroom;
    }

    public String getSchool(){
        return school;
    }

    public String getANDROID_ID(){
        return ANDROID_ID;
    }
}