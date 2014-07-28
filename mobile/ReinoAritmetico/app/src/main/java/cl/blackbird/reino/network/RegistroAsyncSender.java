package cl.blackbird.reino.network;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.HTTP;

import java.util.ArrayList;
import java.util.List;

import cl.blackbird.reino.JoinActivity;

public class RegistroAsyncSender extends AsyncTask<RegistroDataMessage, Void, Boolean> {

    private final String baseRoute;
    private final Context context;
    private String successText;
    private String name,classroom,school;

    public RegistroAsyncSender(Context context, String baseRoute, String successText){
        this.baseRoute = baseRoute;
        this.successText = successText;
        this.context = context;
    }

    @Override
    protected Boolean doInBackground(RegistroDataMessage... params) {
        for(RegistroDataMessage message : params){
            String url = this.baseRoute+message.getRoute();
            HttpClient client = new DefaultHttpClient();
            HttpPost postData = new HttpPost(url);
            try {
                List<NameValuePair> urls = new ArrayList<NameValuePair>();
                name= message.getNombre();
                classroom= message.getCurso();
                school= message.getColegio();
                urls.add(new BasicNameValuePair("name", name));
                urls.add(new BasicNameValuePair("android_id",message.getId()));
                urls.add(new BasicNameValuePair("classroom", classroom));
                urls.add(new BasicNameValuePair("school", school));
                postData.setEntity(new UrlEncodedFormEntity(urls, HTTP.UTF_8));
                HttpResponse response = client.execute(postData);
                int responseCode = response.getStatusLine().getStatusCode();
                if(responseCode < 400){
                    return true;
                } else {
                    Log.e("ASYNC", "Error "+responseCode+" al conectar con "+url);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return false;
        }
        return null;
    }

    @Override
    protected void onPostExecute(Boolean result) {
        if(!result){
            this.successText = "Ocurrió un error";
        }
        else {
            Intent i = new Intent(context, JoinActivity.class);
            i.putExtra("name",name);
            i.putExtra("classroom",classroom);
            i.putExtra("school",school);
            i.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            context.startActivity(i);
            Toast.makeText(this.context, this.successText, Toast.LENGTH_SHORT).show();
        }
    }
}