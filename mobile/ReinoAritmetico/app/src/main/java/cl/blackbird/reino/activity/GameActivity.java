package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.content.Context;
import android.content.Intent;
import android.hardware.SensorManager;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Vibrator;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.net.MalformedURLException;

import cl.blackbird.reino.R;
import cl.blackbird.reino.fragment.LoadingFragment;
import cl.blackbird.reino.fragment.ProblemFragment;
import cl.blackbird.reino.fragment.SensorFragment;
import cl.blackbird.reino.model.Player;
import cl.blackbird.reino.model.Problem;
import io.socket.IOAcknowledge;
import io.socket.IOCallback;
import io.socket.SocketIO;
import io.socket.SocketIOException;

public class GameActivity extends Activity implements IOCallback,
        ProblemFragment.OnAnswerListener, SensorFragment.OnShakeListener {
    private static final String TAG = "RAGAME";
    private boolean gameStarted;
    private Player player;
    private String server;
    private SocketIO socket;
    private long back_pressed;
    private Handler mHandler;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.frame_layout);
        Bundle extras = getIntent().getExtras();
        if(getActionBar() != null){
            getActionBar().setDisplayHomeAsUpEnabled(false);
        }
        server = extras.getString("server");
        player = (Player) extras.getSerializable("player");
        mHandler = new Handler(Looper.getMainLooper());
        if (savedInstanceState == null) {
            gameStarted = false;
            LoadingFragment.setLoadingMessage(this, R.string.joining_game);
        } else {
            gameStarted = savedInstanceState.getBoolean("gameStarted");
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        try {
            socket = new SocketIO(server, this);
            emitJoin();
        } catch (MalformedURLException e) {
            e.printStackTrace();
            setResult(Activity.RESULT_CANCELED);
            finish();
        }
    }

    @Override
    protected void onPause() {
        super.onPause();
        if(socket.isConnected()){
            socket.disconnect();
        }
    }

    /**
     * The user can exit the registry process if he presses the back button twice within 2 seconds.
     */
    @Override
    public void onBackPressed() {
        if (back_pressed + 2000 > System.currentTimeMillis()){
            emitLeave();
        }
        else Toast.makeText(getBaseContext(), R.string.press_twice, Toast.LENGTH_SHORT).show();
        back_pressed = System.currentTimeMillis();
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        outState.putBoolean("gameStarted", gameStarted);
    }

    @Override
    public void onDisconnect() {
        Log.d(TAG, "Socket Disconnected");
    }

    @Override
    public void onConnect() {
        Log.d(TAG, "Socket Connected");
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, ProblemFragment.newInstance(), ProblemFragment.TAG)
                .commit();
    }

    @Override
    public void onMessage(String s, IOAcknowledge ioAcknowledge) {
        Log.d(TAG, String.format("Socket sent: %s", s));
    }

    @Override
    public void onMessage(JSONObject jsonObject, IOAcknowledge ioAcknowledge) {
        Log.d(TAG, String.format("Socket sent: %s", jsonObject.toString()));
    }

    @Override
    public void on(String s, IOAcknowledge ioAcknowledge, Object... objects) {
        Log.d(TAG, String.format("Socket emitted: %s", s));
        Vibrator vibrator = (Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
        vibrator.vibrate(200);
        try {
            if (s.equals("info")) {
                listenInfo((JSONObject) objects[0]);
            } else if (s.equals("error")) {
                listenError((JSONObject) objects[0]);
            } else if (s.equals("solve problem")) {
                listenSolveProblem((JSONObject) objects[0]);
            } else if (s.equals("shake")) {
                listenShakeOrTrap((JSONObject) objects[0], SensorFragment.MODE_SHAKE);
            } else if (s.equals("trapped")) {
                listenShakeOrTrap((JSONObject) objects[0], SensorFragment.MODE_TRAP);
            } else if (s.equals("game end")) {
                listenGameEnd((JSONObject) objects[0]);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

    }

    @Override
    public void onError(SocketIOException e) {
        Log.e(TAG, "Socket Error");
        e.printStackTrace();
        setResult(Activity.RESULT_CANCELED);
        finish();
    }

    @Override
    public void onAnswer(Problem problem) {
        try {
            Log.d(TAG, problem.toJSON().toString());
            socket.emit("submit answer", problem.toJSON());
        } catch (JSONException e) {
            Log.e(TAG, "Error mandando respuesta");
            e.printStackTrace();
        }
    }

    @Override
    public void onShook() {
        socket.emit("shook");
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, ProblemFragment.newInstance(), ProblemFragment.TAG)
                .commit();
    }

    private void emitJoin() {
        JSONObject jsonPlayer = new JSONObject();
        try {
            jsonPlayer.put("name", player.name);
            jsonPlayer.put("android_id", player.androidID);
            jsonPlayer.put("character_type", player.characterType);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        socket.emit("join", jsonPlayer);
        gameStarted = true;
    }

    private void emitLeave() {
        JSONObject jsonPlayer = new JSONObject();
        try {
            jsonPlayer.put("android_id", player.androidID);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        socket.emit("leave",jsonPlayer);
        gameStarted = false;
        setResult(Activity.RESULT_OK);
        finish();
    }

    private void listenInfo(JSONObject json) throws JSONException {
        Log.i(TAG, String.format("Server info: %s", json.get("msg")));
    }

    private void listenError(JSONObject json) throws JSONException {
        Log.e(TAG, String.format("Server error: %s", json.get("msg")));
    }

    private void listenSolveProblem(JSONObject json) throws JSONException {
        Log.i(TAG, String.format("New Problem: %s", json.toString()));
        FragmentManager manager = getFragmentManager();
        Fragment fragment = manager.findFragmentById(R.id.container);
        final Problem problem = Problem.fromJSON(json);
        if(fragment != null){
            if(fragment instanceof ProblemFragment) {
                final ProblemFragment problemFragment = (ProblemFragment) fragment;
                if(Looper.myLooper() == Looper.getMainLooper()){
                    problemFragment.setProblem(problem);
                } else mHandler.post(new Runnable() {
                    @Override
                    public void run() {
                        problemFragment.setProblem(problem);
                    }
                });
            } else {
                if(Looper.myLooper() == Looper.getMainLooper()){
                    replaceProblemFragment(problem);
                } else mHandler.post(new Runnable() {
                    @Override
                    public void run() {
                        replaceProblemFragment(problem);
                    }
                });
            }
        }
    }

    private void replaceProblemFragment(Problem problem){
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, ProblemFragment.newInstance(problem), ProblemFragment.TAG)
                .commit();
    }

    private void listenShakeOrTrap(JSONObject event, final int mode) throws JSONException {
        Log.i(TAG, String.format("%s!: %s", mode == SensorFragment.MODE_SHAKE ? "Shake" : "Trapped", event.toString()));
        if(Looper.myLooper() == Looper.getMainLooper()){
            replaceSensorFragment(mode);
        } else mHandler.post(new Runnable() {
            @Override
            public void run() {
                replaceSensorFragment(mode);
            }
        });
    }

    private void replaceSensorFragment(int mode){
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, SensorFragment.newInstance(mode), SensorFragment.TAG)
                .commit();
    }

    private void listenGameEnd(JSONObject event) throws JSONException {
        Log.i(TAG, String.format("Game end!"));
        Intent result = new Intent();
        result.getExtras().putInt("reward", event.getInt("reward"));
        setResult(Activity.RESULT_OK, result);
        finish();
    }
}
