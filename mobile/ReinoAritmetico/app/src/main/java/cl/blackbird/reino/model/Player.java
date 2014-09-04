package cl.blackbird.reino.model;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;

/**
 * Created by Pablo Albornoz on 7/27/14.
 */
public class Player implements Serializable {
    public String androidID;
    public String name;
    public int characterType;
    public School school;
    public ClassRoom classRoom;

    public Player(String name,int characterType, School school, ClassRoom classRoom) {
        this.androidID = null;
        this.name = name;
        this.characterType =characterType;
        this.school = school;
        this.classRoom = classRoom;
    }

    public Player(String name,int characterType, School school, ClassRoom classRoom, String androidID) {
        this.androidID = androidID;
        this.name = name;
        this.characterType = characterType;
        this.school = school;
        this.classRoom = classRoom;
    }

    public static Player fromJSON(JSONObject json) throws JSONException {
        School tempSchool;
        ClassRoom tempClassRoom;
        String schoolName = null;
        String className = null;
        int schoolID = -1;
        int classID = -1;
        try {
            schoolName = json.getString("school");
            className = json.getString("classroom");
        } catch (JSONException e) {
            schoolID = json.getInt("school");
            classID = json.getInt("classroom");
        }
        tempClassRoom = new ClassRoom(classID, className);
        ArrayList<ClassRoom> classList = new ArrayList<ClassRoom>();
        classList.add(tempClassRoom);
        tempSchool = new School(schoolID, schoolName, classList);
        return new Player(json.getString("name"),Integer.parseInt(json.getString("character_type")), tempSchool, tempClassRoom);
    }
}
