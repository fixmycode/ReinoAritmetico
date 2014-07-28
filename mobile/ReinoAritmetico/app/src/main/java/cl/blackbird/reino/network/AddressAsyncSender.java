package cl.blackbird.reino.network;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.provider.Settings;
import android.util.Log;
import android.widget.EditText;
import android.widget.Toast;

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

import cl.blackbird.reino.GameActivity;
import cl.blackbird.reino.JoinActivity;
import cl.blackbird.reino.R;

/**
 * Created by carlos on 26/7/2014.
 */
public class AddressAsyncSender extends AsyncTask<AddressDataMessage, Void, Boolean> {

    private final String baseRoute;
    private final Context context;
    String address;
    String name;
    String ANDROID_ID;

    public AddressAsyncSender(Context context, String baseRoute){
        this.baseRoute = baseRoute;
        this.context = context.getApplicationContext();
    }
    @Override
    protected Boolean doInBackground(AddressDataMessage... params) {
        for(AddressDataMessage message : params){
            name = message.getName();
            ANDROID_ID = message.getANDROID_ID();
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
                            address = jsonObj.getString("address");

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
        if(result){
            GameDataMessage message = new GameDataMessage(address+"/join", name, ANDROID_ID, address);
            GameAsyncSender sender = new GameAsyncSender(context, message.getRoute().toString());
            sender.execute(message);

        }else{
            Toast.makeText(this.context, "Ha ocurrido un error \n Intente nuevamente", Toast.LENGTH_SHORT).show();
        }
    }
}