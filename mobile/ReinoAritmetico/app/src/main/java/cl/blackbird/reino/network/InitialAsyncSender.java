package cl.blackbird.reino.network;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;

import cl.blackbird.reino.JoinActivity;

/**
 * Created by carlos on 23/7/2014.
 */
public class InitialAsyncSender extends AsyncTask<InitialDataMessage, Void, Boolean> {

    private final String baseRoute;
    private final Context context;
    String name;
    String classroom;
    String school;

    public InitialAsyncSender(Context context, String baseRoute){
        this.baseRoute = baseRoute;
        this.context = context.getApplicationContext();
    }
    @Override
    protected Boolean doInBackground(InitialDataMessage... params) {
        for(InitialDataMessage message : params){
            String url = message.getRoute();
            StringBuilder builder = new StringBuilder();
            try {
                HttpClient client = new DefaultHttpClient();
                HttpGet getData = new HttpGet(url);
                HttpResponse responseGet = client.execute(getData);
                int responseCode = responseGet.getStatusLine().getStatusCode();
                if(responseCode == 404){
                    return false;
                } else {
                    HttpEntity resEntityGet = responseGet.getEntity();
                    InputStream content= resEntityGet.getContent();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(content));
                    String linea;
                    while((linea = reader.readLine())!=null){
                        builder.append(linea);
                    }
                    String jsonStr = builder.toString();

                    if (jsonStr != null) {
                        try {

                            JSONObject jsonObj = new JSONObject(jsonStr);
                            name = jsonObj.getString("name");
                            classroom = jsonObj.getString("classroom");
                            school = jsonObj.getString("school");

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    } else {
                        Log.e("ServiceHandler", "No se pudieron obtener datos");
                    }

                    return true;
                }
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
            intent = new Intent(context, JoinActivity.class);
            intent.putExtra("name",name);
            intent.putExtra("classroom",classroom);
            intent.putExtra("school",school);
            intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            context.startActivity(intent);

        }else{
            InitialDataMessage message = new InitialDataMessage("", "exito");
            Parser sender = new Parser(context,"http://ludus.noip.me/clients");
            sender.execute(message);
        }
    }
}