package cl.blackbird.reino.fragment;

import android.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.Spinner;

import org.json.JSONObject;

import cl.blackbird.reino.R;

/**
 * Created by niko on 19/09/2014.
 */
public class ChangeClassFragment extends Fragment {
    public static final String TAG = "RAFCLASS";
    private Button changeClassButton;
    private Spinner spinner;

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
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.changeclass_layout,container,false);

        spinner= (Spinner)layout.findViewById(R.id.spinner);



        changeClassButton= (Button) layout.findViewById(R.id.cambiar);
        changeClassButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

            }
        });
        return layout;
    }

    @Override
    public void onResume() {
        super.onResume();
    }

    @Override
    public void onPause() {
        super.onPause();
    }
}
