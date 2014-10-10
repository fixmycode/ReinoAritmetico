package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.os.Bundle;
import android.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.Problem;

/**
 * A simple {@link Fragment} subclass.
 * Activities that contain this fragment must implement the
 * {@link cl.blackbird.reino.fragment.ProblemFragment.OnAnswerListener} interface
 * to handle interaction events.
 *
 */
public class ProblemFragment extends Fragment implements View.OnClickListener{
    public static final String TAG = "RAPROBLEMFRAG";
    private OnAnswerListener mListener;
    private Problem currentProblem;
    private long currentTime;
    private TextView answerText;
    private TextView problemText;
    private Button answerButton;
    private Button numbers[];

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.game_layout, container, false);
        answerText = (TextView) layout.findViewById(R.id.answerText);
        problemText = (TextView) layout.findViewById(R.id.problemText);
        answerButton = (Button) layout.findViewById(R.id.answerButton);
        answerButton.setOnClickListener(this);

        Button eraseButton = (Button) layout.findViewById(R.id.eraseButton);
        eraseButton.setOnClickListener(this);

        numbers = new Button[11];

        numbers[0] = (Button) layout.findViewById(R.id.button0);
        numbers[1] = (Button) layout.findViewById(R.id.button1);
        numbers[2] = (Button) layout.findViewById(R.id.button2);
        numbers[3] = (Button) layout.findViewById(R.id.button3);
        numbers[4] = (Button) layout.findViewById(R.id.button4);
        numbers[5] = (Button) layout.findViewById(R.id.button5);
        numbers[6] = (Button) layout.findViewById(R.id.button6);
        numbers[7] = (Button) layout.findViewById(R.id.button7);
        numbers[8] = (Button) layout.findViewById(R.id.button8);
        numbers[9] = (Button) layout.findViewById(R.id.button9);
        numbers[10] = (Button) layout.findViewById(R.id.buttonDot);

        for(Button button : numbers){
            button.setOnClickListener(this);
        }

        if(getArguments() != null){
            this.setProblem((Problem) getArguments().getSerializable("problem"));
        }

        return layout;
    }

    private String getAnswer(){
        return answerText.getText().toString();
    }

    private void setAnswer(String text){
        answerText.setText(text);
    }

    public void answerButtonPressed() {
        if (mListener != null && currentProblem != null) {
            currentProblem.setElapsedTime(System.currentTimeMillis() - currentTime);
            currentProblem.setAnswer(getAnswer());
            mListener.onAnswer(currentProblem);
            setProblem(null);
        }
    }

    public void eraseButtonPressed(){
        String text = getAnswer();
        if(text.length() > 0) setAnswer(text.substring(0, text.length()-1));
    }

    public void numberPressed(Button button) {
        String symbol = button.getText().toString();
        if(!symbol.isEmpty()){
            if(getAnswer().isEmpty()){
                if(symbol.equals("0") || symbol.equals(".")){
                    return;
                }
            }
            setAnswer(getAnswer()+symbol);
        }
    }

    public static ProblemFragment newInstance(Problem problem) {
        ProblemFragment fragment = new ProblemFragment();
        Bundle args = new Bundle();
        args.putSerializable("problem", problem);
        fragment.setArguments(args);
        return fragment;
    }

    public static ProblemFragment newInstance(){
        return ProblemFragment.newInstance(null);
    }

    public void setProblem(Problem problem) {
        this.currentProblem = problem;
        if(currentProblem != null){
            this.currentTime = System.currentTimeMillis();
            this.problemText.setText(problem.getProblem());
        } else {
            this.problemText.setText(R.string.no_question);
        }
        setAnswer("");
        this.answerButton.setEnabled(currentProblem != null);
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnAnswerListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnGameInteractionListener");
        }
    }

    @Override
    public void onDetach() {
        super.onDetach();
        mListener = null;
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.answerButton:
                answerButtonPressed();
                break;
            case R.id.eraseButton:
                eraseButtonPressed();
                break;
            default:
                numberPressed((Button) v);
        }
    }



    public interface OnAnswerListener {
        public void onAnswer(Problem problem);
    }

}
