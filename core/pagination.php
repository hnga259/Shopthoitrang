<?php

class Pagination
{
    protected $current_page;
    protected $total_page;
    public function __construct($total_page, $current_page)
    {
        $this->total_page = $total_page;
        $this->current_page = $current_page;
    }
    public function getLinkPage($page)
    {
        $query = $_GET;
        $query['page'] = $page;
        $new_query = http_build_query($query);
        $url  = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]" . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        return $url . "?" . $new_query;
    }
    public function render()
    {
        $pagination_html = "<nav aria-label='Page navigation' class='text-right'><ul class='pagination'>";
        if ($this->current_page > 1) {
            $pagination_html .= "<li><a href='" . $this->getLinkPage($this->current_page - 1) . "' aria-hidden='true'>&laquo;</a></li>";
        }
        for ($i = 1; $i <= $this->total_page; $i++) {
            if ($i == $this->current_page) {
                $pagination_html .= "<li class='active'><a href='javascript:void(0)'>$i</a></li>";
            } else {
                $pagination_html .= "<li><a href='" . $this->getLinkPage($i) . "'>$i</a></li>";
            }
        }
        if ($this->current_page < $this->total_page) {
            $pagination_html .= "<li><a href='" . $this->getLinkPage($this->current_page + 1) . "' aria-hidden='true'>&raquo;</a></li>";
        }
        $pagination_html .=  "</ul></nav>";
        return $pagination_html;
    }
}
