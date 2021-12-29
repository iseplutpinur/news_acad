package id.my.iseplutpi.newsacad;

import java.util.List;

import id.my.iseplutpi.newsacad.model.NewsHeadline;

public interface OnFetchDataListener<NewsApiResponse>  {
    void onFetchData(List<NewsHeadline> list, String message);

    void onError(String message);
}
