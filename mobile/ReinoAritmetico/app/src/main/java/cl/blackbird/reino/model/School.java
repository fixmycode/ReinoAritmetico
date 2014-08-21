package cl.blackbird.reino.model;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by Pablo Albornoz on 7/27/14.
 */
public class School implements Serializable {
    public int id;
    public String name;
    public List<ClassRoom> classRooms;

    public School(int id, String name, List<ClassRoom> classRooms){
        this.id = id;
        this.name = name;
        this.classRooms = classRooms;
    }

    public static School fromJSON(JSONObject json) throws JSONException {
        JSONArray classArray = json.getJSONArray("classrooms");
        List<ClassRoom> classRoomList = new ArrayList<ClassRoom>();
        for(int i = 0; i < classArray.length(); i++){
            classRoomList.add(ClassRoom.fromJSON(classArray.getJSONObject(i)));
        }
        return new School(json.getInt("id"), json.getString("name"), classRoomList);
    }

    @Override
    public String toString() {
        return this.name;
    }
}
