package cl.blackbird.reino.personaje;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import cl.blackbird.reino.R;

/**
 * Created by niko on 31/08/2014.
 */
public class ViewPagerFragment extends Fragment {
    private static final String POSITION = "position";
    private static final String IMAGEID = "imageId";

    public ViewPagerFragment(){

    }

    public static ViewPagerFragment newInstance(int position,int imageId){
        ViewPagerFragment frag = new ViewPagerFragment();
        Bundle bundle = new Bundle();
        bundle.putInt(POSITION,position);
        bundle.putInt(IMAGEID,imageId);
        frag.setArguments(bundle);
        return frag;
    }
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.mf,container,false);

        int ImageId = getArguments().getInt(IMAGEID);
        int position = getArguments().getInt(POSITION);

        ImageView iv = (ImageView) view.findViewById(R.id.imageView);
        iv.setImageResource(ImageId);
        TextView tv = (TextView) view.findViewById(R.id.text);
        tv.setText("Imagen no: "+ position);
        Toast.makeText(getActivity(), "page number: " + position, Toast.LENGTH_SHORT).show();
        return view;
    }
}
