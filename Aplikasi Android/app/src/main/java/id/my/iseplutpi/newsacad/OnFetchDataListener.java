package id.my.iseplutpi.newsacad;

import java.util.List;

import id.my.iseplutpi.newsacad.model.NewsHeadlines;

public interface OnFetchDataListener<NewsApiResponse>  {
    void onFetchData(List<NewsHeadlines> list, String message);

    void onError(String message);
}
