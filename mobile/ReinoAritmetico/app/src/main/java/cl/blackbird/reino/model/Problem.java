package cl.blackbird.reino.model;

import org.json.JSONException;
import org.json.JSONObject;

public class Problem {
    private int id;
    private String problem;
    private String correctAnswer;
    private String answer;
    private long elapsedTime;

    private Problem (int id, String problem, String correctAnswer, String answer, long elapsedTime) {
        this.id = id;
        this.problem = problem;
        this.correctAnswer = correctAnswer;
        this.answer = answer;
        this.elapsedTime = elapsedTime;
    }

    private Problem (int id, String problem, String correctAnswer) {
        this(id, problem, correctAnswer, null, -1);
    }

    public static Problem fromJSON(JSONObject obj) throws JSONException {
        return new Problem(obj.getInt("problem_id"), obj.getString("problem"), obj.getString("correct_answer"));
    }

    public JSONObject toJSON() throws JSONException {
        JSONObject obj = new JSONObject();
        obj.put("problem_id", id);
        obj.put("problem", problem);
        obj.put("correct_answer", correctAnswer);
        obj.put("answer", answer);
        obj.put("elapsed_time", elapsedTime);
        return obj;
    }

    public void setElapsedTime(long time) {
        this.elapsedTime = time;
    }

    public void setAnswer(String answer) {
        this.answer = answer;
    }

    public String getProblem() {
        return problem;
    }

    public String getAnswer() {
        return answer;
    }
}
