package cl.blackbird.reino;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;


public class MenuActivity extends Activity {
	@Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu);
    }
     
	public void jugar(View view){
		Intent i = new Intent(this,GameActivity.class);
		startActivity(i);
	}
	public void detalles(View view){
		
	}
	public void salir(View view){
		finish();
	}
}
