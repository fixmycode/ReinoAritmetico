package cl.blackbird.reino.network;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.StatusLine;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.lang.reflect.Array;
import java.util.ArrayList;
import java.util.List;

import cl.blackbird.reino.JoinActivity;
import cl.blackbird.reino.Register;

/**
 * Created by niko on 27/07/2014.
 */
public class Parser extends AsyncTask<InitialDataMessage, Void, Boolean> {
    String baseRoute;
    Context context;
    static String response=null;
    private static final String id="id";
    private static final String cursos="classrooms";
    private static final String name="name";
    private final int colegios=0;
    JSONArray jr = null;
    JSONArray classroom= null;
    ArrayList<School> schools = new ArrayList<School>();
    ArrayList<Classroom> classrooms= new ArrayList<Classroom>();

    public Parser(Context context, String baseRoute){
        this.baseRoute = baseRoute;
        this.context = context.getApplicationContext();
    }

    @Override
    protected Boolean doInBackground(InitialDataMessage... params) {
        for(InitialDataMessage message : params){
            String url = this.baseRoute+message.getRoute();
            HttpClient client = new DefaultHttpClient();
            HttpGet getData = new HttpGet(url);
            try {
                HttpResponse httpResponse = client.execute(getData);
                StatusLine sl = httpResponse.getStatusLine();
                int sc = sl.getStatusCode();
                if(sc==200){
                    HttpEntity resEntityGet = httpResponse.getEntity();
                    response= EntityUtils.toString(resEntityGet);
                    if(response!=null) {
                        try {
                            jr = new JSONArray(response);
                            for (int i =0;i<jr.length();i++) {
                                JSONObject v = jr.getJSONObject(i);
                                String ids = v.getString(id);
                                String school = v.getString(name);


                                classroom = v.getJSONArray(cursos);
                                for (int j = 0; j < classroom.length(); j++) {
                                    JSONObject c = classroom.getJSONObject(j);
                                    String cr= c.getString(name);
                                    String idc = c.getString(id);
                                    classrooms.add(new Classroom(idc,cr));

                                }
                                schools.add(new School(ids,school,classrooms));
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }

                    return true;

                }
                else{
                    Log.e(Parser.class.toString(), "Failed to download file");
                }
            } catch (ClientProtocolException e) {
                e.printStackTrace();
            } catch (Exception e) {
                e.printStackTrace();
            }
            return false;
        }

        return null;
    }
    protected void onPostExecute(Boolean result) {
        super.onPostExecute(result);
        Intent intent;
        if(result){
            /*Bundle extra= new Bundle();
            extra.putSerializable("colegio", schools);*/
            intent = new Intent(context, Register.class);
            intent.putExtra("extra",schools);

        }else{
            intent = new Intent(context, JoinActivity.class);
        }
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        context.startActivity(intent);
    }

}