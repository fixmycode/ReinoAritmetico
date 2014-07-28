package cl.blackbird.reino.network;

import java.io.Serializable;
import java.util.ArrayList;

/**
 * Created by niko on 27/07/2014.
 */
public class Classroom extends ArrayList implements Serializable{
    public String id;
    public String name;
    public Classroom(String id, String name){
        this.id= id;
        this.name=name;
    }
    @Override
    public String toString(){
        return this.name;
    }


}