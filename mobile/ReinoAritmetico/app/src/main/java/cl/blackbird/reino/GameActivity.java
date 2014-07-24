package cl.blackbird.reino;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.Toast;

public class GameActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_play);
			
	}
	public void enviar(View view){
		Toast.makeText(getApplicationContext(), "Se ha enviado el archivo", Toast.LENGTH_LONG).show();
	}
	

}
