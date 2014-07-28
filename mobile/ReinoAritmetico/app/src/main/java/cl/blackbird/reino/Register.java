package cl.blackbird.reino;

import android.app.Activity;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.Spinner;

import java.util.ArrayList;

import cl.blackbird.reino.network.Classroom;
import cl.blackbird.reino.network.RegistroAsyncSender;
import cl.blackbird.reino.network.RegistroDataMessage;
import cl.blackbird.reino.network.School;

/**
 * Created by niko on 27/07/2014.
 */
public class Register extends Activity {
    private final String servidor="http://ludus.noip.me/register";
    ArrayList<School> schoolList= new ArrayList<School>();
    ArrayList<Classroom> classroomsList= new ArrayList<Classroom>();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_registro);
        schoolList=(ArrayList<School>) getIntent().getSerializableExtra("extra");
        Spinner spi=(Spinner) findViewById(R.id.spinner1);

        ArrayAdapter<School> schoolAdapter= new ArrayAdapter<School>(this,android.R.layout.simple_spinner_item,schoolList);
        schoolAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spi.setAdapter(schoolAdapter);
        spi.setOnItemSelectedListener(new OnItemSelectedListener() {
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {

                Spinner spi2=(Spinner) findViewById(R.id.spinner2);
                School s=(School)parent.getAdapter().getItem(position);
                ArrayAdapter<Classroom> classAdapter = new ArrayAdapter<Classroom>(parent.getContext(),android.R.layout.simple_spinner_item,s.getClassrooms());
                classAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spi2.setAdapter(classAdapter);

                //No estoy seguro si es necesario esta implementacion, pero funciona
                spi2.setOnItemSelectedListener(new OnItemSelectedListener() {
                    @Override
                    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                        Classroom c= (Classroom)parent.getAdapter().getItem(position);
                    }

                    @Override
                    public void onNothingSelected(AdapterView<?> parent) {

                    }
                });
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });


    }

    public void registro(View view){
        EditText name=(EditText)findViewById(R.id.editText1);
        String nombre= name.getText().toString();
        final String aid = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);

        Spinner spi=(Spinner)findViewById(R.id.spinner1);
        String colegio= ((School)spi.getSelectedItem()).id;

        Spinner spi2=(Spinner)findViewById(R.id.spinner2);
        String curso= ((Classroom)spi2.getSelectedItem()).id;

        RegistroDataMessage message= new RegistroDataMessage("",aid,nombre,curso,colegio);

        if(nombre!= null&& !name.equals("")){
            RegistroAsyncSender sender = new RegistroAsyncSender(getApplicationContext(),servidor,"Registrado exitosamente");
            sender.execute(message);
        }

    }
}