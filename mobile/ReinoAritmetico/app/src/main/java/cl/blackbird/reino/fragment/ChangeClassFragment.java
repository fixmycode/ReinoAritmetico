package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.Fragment;
import android.content.res.TypedArray;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;

import org.json.JSONObject;

import cl.blackbird.reino.R;

/**
 * Created by niko on 19/09/2014.
 */
public class ChangeClassFragment extends Fragment {
    public static final String TAG = "RAFCLASS";
    private Button changeClassButton;
    private Spinner charSpinner;
    private int clase;
    private int costo;
    private ImageView charImage;
    private String[] charTypes;
    private changeListener cListener;
    private TypedArray charDrawables;

    public ChangeClassFragment(){

    }
    public static ChangeClassFragment newInstance(int clase){
        ChangeClassFragment fragment = new ChangeClassFragment();
        Bundle args = new Bundle();
        args.putInt("clase", clase);
        fragment.setArguments(args);
        return fragment;
    }
    @Override
     public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            cListener = (changeListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnFragmentInteractionListener");
        }
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.changeclass_layout,container,false);


        charTypes = getResources().getStringArray(R.array.characters);
        charDrawables = getResources().obtainTypedArray(R.array.character_list);
        charImage = (ImageView) layout.findViewById(R.id.imageView);
        charSpinner= (Spinner)layout.findViewById(R.id.spinner);
        changeClassButton= (Button) layout.findViewById(R.id.cambiar);
        changeClassButton.setEnabled(false);

        if(getArguments()!=null) {
            setSpinner();
            //setButton();
        }
        return layout;
    }
    public void setSpinner(){
        ArrayAdapter<String> dataAdapter  = new ArrayAdapter<String>(
                getActivity(),android.R.layout.simple_spinner_item, charTypes);
        dataAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        charSpinner.setAdapter(dataAdapter);
        charSpinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                charImage.setImageResource(charDrawables.getResourceId(
                        charSpinner.getSelectedItemPosition(), -1));
                Log.d("clase",String.valueOf(charSpinner.getSelectedItemPosition()));
                setButton();

            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });

    }
    public void setButton(){
        costo=500;
        clase = getArguments().getInt("clase");

        changeClassButton.setEnabled(true);
        if(clase == charSpinner.getSelectedItemPosition()){
            changeClassButton.setText("Cambiar Clase: $" + 0);
            changeClassButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Toast.makeText(getActivity(),"Ya tienes esa clase",Toast.LENGTH_SHORT).show();
                }
            });
        }
        else {
            changeClassButton.setText("Cambiar Clase: $" + costo);
            changeClassButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (cListener != null){
                        cListener.onChangeClick(charSpinner.getSelectedItemPosition(),costo);
                    }
                }
            });
        }
    }

    @Override
    public void onResume() {
        super.onResume();
    }

    @Override
    public void onPause() {
        super.onPause();
    }

    public interface changeListener{
        public void onChangeClick(int clase,int precio);
    }
}
