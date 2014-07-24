package cl.blackbird.reino;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;

import cl.blackbird.reino.network.RegistroAsyncSender;
import cl.blackbird.reino.network.RegistroDataMessage;

public class RegistroActivity extends Activity{
    private final String servidor="http://192.168.0.112";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // TODO Auto-generated method stub
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_registro);

        Spinner spiner1= (Spinner)findViewById(R.id.spinner1);
        ArrayAdapter<CharSequence> adapter1= ArrayAdapter.createFromResource(this, R.array.Select_colegio, android.R.layout.simple_spinner_item);

        Spinner spiner2= (Spinner)findViewById(R.id.spinner2);
        ArrayAdapter<CharSequence> adapter2= ArrayAdapter.createFromResource(this, R.array.Select_curso, android.R.layout.simple_spinner_item);

        adapter1.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spiner1.setAdapter(adapter1);

        adapter2.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spiner2.setAdapter(adapter2);

    }
    public void registro(View view){
        EditText name=(EditText)findViewById(R.id.editText1);
        String nombre= name.getText().toString();

        Spinner spi=(Spinner)findViewById(R.id.spinner1);
        String colegio= spi.getSelectedItem().toString();
        //Toast.makeText(getApplicationContext(),Colegio,Toast.LENGTH_LONG).show();

        Spinner spi2=(Spinner)findViewById(R.id.spinner2);
        String curso= spi2.getSelectedItem().toString();
        //Toast.makeText(getApplicationContext(),Curso,Toast.LENGTH_LONG).show();
        RegistroDataMessage message= new RegistroDataMessage("","12",nombre,colegio,curso);

        if(nombre!= null&& !name.equals("")){
            RegistroAsyncSender sender = new RegistroAsyncSender(getApplicationContext(),servidor,"enviado exitosamente");
            sender.execute(message);
            //el asyncsender debería ir en la actividad de LoadActivity utilizando getExtras
            /*Intent i = new Intent(RegistroActivity.this,LoadActivity.class);
            RegistroActivity.this.startActivity(i);
            RegistroActivity.this.finish();*/
        }


    }

}