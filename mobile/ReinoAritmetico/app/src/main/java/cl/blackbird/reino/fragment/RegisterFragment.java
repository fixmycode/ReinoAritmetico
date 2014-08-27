package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.app.Fragment;
import android.provider.Settings;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;

import org.json.JSONArray;
import org.json.JSONException;

import java.util.ArrayList;
import java.util.List;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.ClassRoom;
import cl.blackbird.reino.model.Player;
import cl.blackbird.reino.model.School;

public class RegisterFragment extends Fragment implements AdapterView.OnItemSelectedListener, View.OnClickListener {

    public static final String TAG = "RAFREGISTER";
    private static final String CLIENTS_STRING = "clients";
    private Spinner schoolSpinner;
    private Spinner classSpinner;
    private EditText nameField;
    private RegisterListener registerListener;
    private Button registerButton;

    /**
     * This is a factory method (remember the Factory pattern from FISW?) to create a new Register
     * Fragment, it allows to pass the client list the right way.
     * @param clientsArray a JSON array of clients
     * @return the new fragment instance
     */
    public static RegisterFragment newInstance(JSONArray clientsArray) {
        RegisterFragment fragment = new RegisterFragment();
        Bundle args = new Bundle();
        args.putString(CLIENTS_STRING, clientsArray.toString());
        fragment.setArguments(args);
        return fragment;
    }

    public RegisterFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    /**
     * This method loads the fragment's UI, we also bind listeners and other things to the UI here.
     * @return the inflated layout
     */
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.registry_form, container, false);
        nameField = (EditText) layout.findViewById(R.id.full_name);
        registerButton = (Button) layout.findViewById(R.id.register_button);
        checkValidForm(nameField.getText(), registerButton);
        nameField.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                checkValidForm(s, registerButton);
            }
        });

        if (getArguments() != null) {
            try {
                JSONArray clients = new JSONArray(getArguments().getString(CLIENTS_STRING));
                buildSpinners(layout, clients);
                buildButton(layout);
            } catch (JSONException e) {
                Log.e(TAG, "Error reading clients");
                registerError(R.string.server_error);
            }
        }
        return layout;
    }

    /**
     * Checks if the form is not empty and then enable the button
     */
    private void checkValidForm(Editable text, Button button) {
        button.setEnabled(!text.toString().isEmpty());
    }

    /**
     * Binds the register button to its listener (this fragment)
     */
    private void buildButton(View layout) {
        Button registerButton = (Button) layout.findViewById(R.id.register_button);
        registerButton.setOnClickListener(this);
    }

    /**
     * This builds the spinner for the form. Note how I used the model classes to transform the JSON
     * files into my dynamic models.
     */
    private void buildSpinners(View layout, JSONArray clients) throws JSONException {
        schoolSpinner = (Spinner) layout.findViewById(R.id.school_spinner);
        classSpinner = (Spinner) layout.findViewById(R.id.class_spinner);
        classSpinner.setEnabled(false);

        List<School> schoolList = new ArrayList<School>();
        for(int i = 0; i < clients.length(); i++){
            schoolList.add(School.fromJSON(clients.getJSONObject(i)));
        }

        ArrayAdapter<School> schoolAdapter = new ArrayAdapter<School>(
                getActivity(), android.R.layout.simple_spinner_item, schoolList);

        schoolAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        schoolSpinner.setAdapter(schoolAdapter);
        schoolSpinner.setOnItemSelectedListener(this);
    }

    /**
     * Tells the underlying activity that we had an error
     * @param message the message for the activity
     */
    public void registerError(int message) {
        if (registerListener != null) {
            registerListener.onRegisterError(message);
        }
    }

    /**
     * When we attach this fragment to an activity, we bind it as a listener
     */
    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            registerListener = (RegisterListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnFragmentInteractionListener");
        }
    }

    /**
     * When we detach, we destroy the listener
     */
    @Override
    public void onDetach() {
        super.onDetach();
        registerListener = null;
    }

    /**
     * What happens when you choose an option on a spinner
     */
    @Override
    public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
        classSpinner.setEnabled(true);
        School school = (School) schoolSpinner.getItemAtPosition(position);
        ArrayAdapter<ClassRoom> classAdapter = new ArrayAdapter<ClassRoom>(getActivity(),
                android.R.layout.simple_spinner_item, school.classRooms);
        classAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        classSpinner.setAdapter(classAdapter);
    }

    @Override
    public void onNothingSelected(AdapterView<?> parent) {
        //do nothing
    }

    /**
     * When we press the Register button
     */
    @Override
    public void onClick(View v) {
        final AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
        final String name = nameField.getText().toString();
        //get id from IMAGEVIEW fragmento
        final String pjID ="2";
        final School selectedSchool = (School) schoolSpinner.getSelectedItem();
        final ClassRoom selectedClassRoom = (ClassRoom) classSpinner.getSelectedItem();
        String message = String.format(getString(R.string.register_dialog),
                name,pjID, selectedSchool.name, selectedClassRoom.name);
        builder.setMessage(message);
        builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                registerNewPlayer(name,pjID, selectedSchool, selectedClassRoom);
                dialog.dismiss();
            }
        });
        builder.setNegativeButton(R.string.no, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.cancel();
            }
        });
        AlertDialog dialog = builder.create();
        dialog.show();
    }

    /**
     * This method creates a new Player object and notifies the underlying activity
     * @param name the student's name
     * @param school the school
     * @param classRoom the classroom
     */
    private void registerNewPlayer(final String name,String pjID, School school, ClassRoom classRoom) {
        Player player = new Player(name,pjID, school, classRoom);
        player.androidID = Settings.Secure.getString(
                getActivity().getContentResolver(),
                Settings.Secure.ANDROID_ID);
        if (registerListener != null){
            registerListener.onRegisterPlayer(player);
        }
    }

    /**
     * Interface for the the fragment to communicate with underlying activity.
     */
    public interface RegisterListener {
        public void onRegisterPlayer(Player player);
        public void onRegisterError(int message);
    }
}
