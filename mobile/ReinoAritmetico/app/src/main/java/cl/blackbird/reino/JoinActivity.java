package cl.blackbird.reino;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.provider.Settings;
import android.view.View;
import android.widget.EditText;

import cl.blackbird.reino.network.AddressAsyncSender;
import cl.blackbird.reino.network.AddressDataMessage;
import cl.blackbird.reino.network.InitialAsyncSender;
import cl.blackbird.reino.network.InitialDataMessage;
import cl.blackbird.reino.network.RemoveAsyncSender;


public class JoinActivity extends Activity {
    String name, classroom,school, ANDROID_ID;
	@Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_join);
        final String ANDROID_ID = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        name = getIntent().getStringExtra("name");
        classroom = getIntent().getStringExtra("classroom");
        school = getIntent().getStringExtra("school");
    }
     
	public void join(View view){
        EditText UIDtxt =(EditText)findViewById(R.id.editText);
        String UID = UIDtxt.getText().toString();
        final String ANDROID_ID = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        AddressDataMessage message = new AddressDataMessage("http://ludus.noip.me/server?uid="+UID, name, classroom, school,ANDROID_ID);
        AddressAsyncSender sender = new AddressAsyncSender(JoinActivity.this, message.getRoute().toString());
        sender.execute(message);
	}
}
