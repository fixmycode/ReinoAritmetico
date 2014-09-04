package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.FragmentTransaction;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.net.MalformedURLException;

import cl.blackbird.reino.R;
import cl.blackbird.reino.fragment.LoadingFragment;
import cl.blackbird.reino.fragment.MainGameFragment;
import cl.blackbird.reino.model.Player;
import cl.blackbird.reino.model.Problem;
import io.socket.IOAcknowledge;
import io.socket.IOCallback;
import io.socket.SocketIO;
import io.socket.SocketIOException;

public class GameActivity extends Activity implements IOCallback, MainGameFragment.OnGameInteractionListener {
    private static final String TAG = "RAGAME";
    private boolean gameStarted;
    private Player player;
    private SocketIO socket;
    private long back_pressed;
    private MainGameFragment gameFragment;
    private Handler mHandler;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.frame_layout);
        Bundle extras = getIntent().getExtras();
        if(getActionBar() != null){
            getActionBar().setDisplayHomeAsUpEnabled(false);
        }
        try {
            socket = new SocketIO(extras.getString("server"));
            player = (Player) extras.getSerializable("player");
            mHandler = new Handler(Looper.getMainLooper());
            if (savedInstanceState == null) {
                gameStarted = false;
                LoadingFragment.setLoadingMessage(this, R.string.joining_game);
            } else {
                gameStarted = savedInstanceState.getBoolean("gameStarted");
            }
        } catch (MalformedURLException e) {
            e.printStackTrace();
            setResult(Activity.RESULT_CANCELED);
            finish();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        if(!socket.isConnected()){
            socket.connect(this);
            if(!gameStarted){
                emitJoin();
            }
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
        gameFragment = new MainGameFragment();
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, gameFragment)
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
        try {
            if (s.equals("info")) {
                listenInfo((JSONObject) objects[0]);
            } else if (s.equals("error")) {
                listenError((JSONObject) objects[0]);
            } else if (s.equals("solve problem")) {
                listenSolveProblem((JSONObject) objects[0]);
            } else if (s.equals("game end")) {
                listenGameEnd();
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
    public void onSendResponse(Problem problem) {
        try {
            Log.d(TAG, problem.toJSON().toString());
            socket.emit("submit answer", problem.toJSON());
            postProblem(null);
        } catch (JSONException e) {
            Log.e(TAG, "Error mandando respuesta");
            e.printStackTrace();
        }
    }

    private void emitJoin() {
        JSONObject jsonPlayer = new JSONObject();
        try {
            jsonPlayer.put("name", player.name);
            jsonPlayer.put("android_id", player.androidID);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        socket.emit("join",jsonPlayer);
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

    private void listenSolveProblem(JSONObject problem) throws JSONException {
        Log.i(TAG, String.format("New Problem: %s", problem.toString()));
        postProblem(Problem.fromJSON(problem));
    }

    private void listenGameEnd() {
        Log.i(TAG, String.format("Game end!"));
        setResult(Activity.RESULT_OK);
        finish();
    }

    public void postProblem(final Problem problem) {
        if(gameFragment == null) return;
        if(Looper.myLooper() == Looper.getMainLooper()){
            gameFragment.setProblem(problem);
        } else {
            mHandler.post(new Runnable() {
                @Override
                public void run() {
                    gameFragment.setProblem(problem);
                }
            });
        }
    }
}
