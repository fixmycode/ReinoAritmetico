package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.content.Context;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.os.Bundle;
import android.app.Fragment;
import android.util.FloatMath;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import cl.blackbird.reino.R;

public class SensorFragment extends Fragment implements SensorEventListener {
    private static final float GRAVITY_THRESHOLD = 2.7f;
    private static final int REPEAT_THRESHOLD = 200;
    private static final int RESET_TIME = 2000;
    private static final int SHOOK_THRESHOLD = 10;
    public static final String TAG = "RASENSORFRAG";

    private SensorManager sensorManager;

    private long lastUpdate;
    private int shakeCount;

    public static final int MODE_SHAKE = 0;
    public static final int MODE_TRAP = 1;

    private int mode = MODE_SHAKE;
    private String msg = "";

    private OnShakeListener mListener;

    public static SensorFragment newInstance(int mode, String msg) {
        SensorFragment fragment = new SensorFragment();
        Bundle args = new Bundle();
        args.putInt("mode", mode);
        args.putString("msg", msg);
        fragment.setArguments(args);
        return fragment;
    }
    public SensorFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mode = getArguments().getInt("mode");
            msg = getArguments().getString("msg");
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View layout = inflater.inflate(R.layout.sensor_layout, container, false);
        RelativeLayout background = (RelativeLayout) layout.findViewById(R.id.background);
        ImageView image = (ImageView) layout.findViewById(R.id.image);
        TextView message = (TextView) layout.findViewById(R.id.message);
        int colorId, imageId;
        if(mode == MODE_SHAKE){
            colorId = R.color.blue;
            imageId = R.drawable.shake;
        } else {
            colorId = R.color.red;
            imageId = R.drawable.trap;
        }
        background.setBackgroundResource(colorId);
        image.setImageResource(imageId);
        message.setText(msg);
        message.setGravity(Gravity.CENTER_HORIZONTAL);
        return layout;
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnShakeListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnFragmentInteractionListener");
        }
    }

    @Override
    public void onDetach() {
        super.onDetach();
        mListener = null;
    }

    @Override
    public void onResume() {
        super.onResume();
        if(mode == MODE_SHAKE) {
            lastUpdate = System.currentTimeMillis();
            sensorManager = (SensorManager) getActivity().getSystemService(Context.SENSOR_SERVICE);
            Sensor accelerometer = sensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER);
            sensorManager.registerListener(this, accelerometer, SensorManager.SENSOR_DELAY_GAME);
        }
    }

    @Override
    public void onPause() {
        super.onPause();
        if(mode == MODE_SHAKE) sensorManager.unregisterListener(this);
    }

    @Override
    public void onSensorChanged(SensorEvent event) {
        if(mListener == null) return;
        Sensor sensor = event.sensor;
        if(sensor.getType() == Sensor.TYPE_ACCELEROMETER){
            float x = event.values[0];
            float y = event.values[1];
            float z = event.values[2];

            float gX = x / SensorManager.GRAVITY_EARTH;
            float gY = y / SensorManager.GRAVITY_EARTH;
            float gZ = z / SensorManager.GRAVITY_EARTH;

            float gForce = FloatMath.sqrt((gX * gX) + (gY * gY) + (gZ * gZ));

            if(gForce > GRAVITY_THRESHOLD) {
                long now = System.currentTimeMillis();
                Log.i(TAG, String.format("Passed gravity threshold: %f", gForce));
                if((now - lastUpdate) < REPEAT_THRESHOLD) return;
                Log.i(TAG, String.format("Passed repeat threshold: %d", (now-lastUpdate)));
                if((now - lastUpdate) > RESET_TIME){
                    shakeCount = 0;
                    Log.i(TAG, "Shake count reset!");
                }

                shakeCount++;
                Log.i(TAG, String.format("Shake: #%d", shakeCount));

                if(shakeCount > SHOOK_THRESHOLD){
                    shakeCount = 0;
                    mListener.onShook();
                }
                lastUpdate = now;
            }
        }
    }

    @Override
    public void onAccuracyChanged(Sensor sensor, int accuracy) {

    }

    public interface OnShakeListener {
        public void onShook();
    }

}
