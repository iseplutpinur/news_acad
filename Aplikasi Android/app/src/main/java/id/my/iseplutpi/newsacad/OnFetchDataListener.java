package id.my.iseplutpi.newsacad;

import java.util.List;

import id.my.iseplutpi.newsacad.model.News;

public interface OnFetchDataListener<NewsApiResponse>  {
    void onFetchData(List<News> list, String message);

    void onError(String message);
}
