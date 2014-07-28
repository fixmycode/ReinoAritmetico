package cl.blackbird.reino.network;

import org.apache.http.message.BasicNameValuePair;

import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.ObjectStreamException;
import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * Created by niko on 27/07/2014.
 */
public class School implements Serializable {
    public String id;
    public String name;
    public ArrayList<Classroom> classrooms;
    public School(String id, String name, ArrayList<Classroom> classroom){
        this.id= id;
        this.name= name;
        this.classrooms=classroom;
    }
    @Override
    public String toString(){
        return this.name;
    }
    public ArrayList<Classroom> getClassrooms(){
        return classrooms;
    }

}