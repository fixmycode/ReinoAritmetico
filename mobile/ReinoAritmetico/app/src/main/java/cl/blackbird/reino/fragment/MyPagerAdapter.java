package cl.blackbird.reino.fragment;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;

import cl.blackbird.reino.activity.RegisterActivity;
import cl.blackbird.reino.R;

public class MyPagerAdapter extends FragmentPagerAdapter implements
        ViewPager.OnPageChangeListener {

    private MyLinearLayout cur = null;
    private MyLinearLayout next = null;
    private RegisterActivity context;
    private FragmentManager fm;
    private float scale;

    public MyPagerAdapter(RegisterActivity context, FragmentManager fm) {
        super(fm);
        this.fm = fm;
        this.context = context;
    }

    @Override
    public Fragment getItem(int position)
    {
        // make the first pager bigger than others
        if (position == RegisterActivity.FIRST_PAGE)
            scale = RegisterActivity.BIG_SCALE;
        else
            scale = RegisterActivity.SMALL_SCALE;

        position = position % RegisterActivity.PAGES;
        return MyFragment.newInstance(context, position, scale);
    }

    @Override
    public int getCount()
    {
        return RegisterActivity.PAGES * RegisterActivity.LOOPS;
    }

    @Override
    public void onPageScrolled(int position, float positionOffset,
                               int positionOffsetPixels)
    {
        if (positionOffset >= 0f && positionOffset <= 1f)
        {
            cur = getRootView(position);
            next = getRootView(position +1);

            cur.setScaleBoth(RegisterActivity.BIG_SCALE
                    - RegisterActivity.DIFF_SCALE * positionOffset);
            next.setScaleBoth(RegisterActivity.SMALL_SCALE
                    + RegisterActivity.DIFF_SCALE * positionOffset);
        }
    }

    @Override
    public void onPageSelected(int position) {}

    @Override
    public void onPageScrollStateChanged(int state) {}

    private MyLinearLayout getRootView(int position)
    {
        return (MyLinearLayout)
                fm.findFragmentByTag(this.getFragmentTag(position))
                        .getView().findViewById(R.id.root);
    }

    private String getFragmentTag(int position)
    {
        return "android:switcher:" + context.pager.getId() + ":" + position;
    }
}
