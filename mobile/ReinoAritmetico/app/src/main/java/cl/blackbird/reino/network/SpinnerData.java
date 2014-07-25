package cl.blackbird.reino.network;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;
import android.view.Menu;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.StatusLine;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;

import cl.blackbird.reino.JoinActivity;
import cl.blackbird.reino.RegistroActivity;

/**
 * Created by niko on 24/07/2014.
 */
public class SpinnerData extends AsyncTask<InitialDataMessage, Void, Boolean> {

    private final String baseRoute;
    private final Context context;

    public SpinnerData(Context context, String baseRoute){
        this.baseRoute = baseRoute;
        this.context = context.getApplicationContext();
    }
    @Override
    protected Boolean doInBackground(InitialDataMessage... params) {
        for(InitialDataMessage message : params){
            String url = this.baseRoute+message.getRoute();
            StringBuilder builder = new StringBuilder();
            HttpClient client = new DefaultHttpClient();
            HttpGet getData = new HttpGet(url);
            try {
                HttpResponse response = client.execute(getData);
                StatusLine sl = response.getStatusLine();
                int sc = sl.getStatusCode();
                if(sc==200){
                    HttpEntity resEntityGet = response.getEntity();
                    InputStream content= resEntityGet.getContent();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(content));
                    String linea;
                    while((linea= reader.readLine())!=null){
                        builder.append(linea);
                    }
                    System.out.println(builder.toString());
                    return true;
                }
                else{
                    Log.e(SpinnerData.class.toString(), "Failed to download file");
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
            intent = new Intent(context, RegistroActivity.class);
        }else{
            intent = new Intent(context, JoinActivity.class);
        }
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        context.startActivity(intent);
    }
}