<?php
class product extends control
{
    public function getApiProductList()
    {
        $data = $this->product->getApiProductList('wait');
a($data);die();
        $result = new stdClass();
        $result->status = 'success';
        $result->data   = array(
            'a' => 1,
            'b' => 1,
            'c' => 1,
        );
        $this->send(array('status' => 'success', 'result' => $result));
    }
}
