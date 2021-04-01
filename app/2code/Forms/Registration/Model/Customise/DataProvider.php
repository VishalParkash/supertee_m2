<?php
public function getData()
{	   
	if (isset($this->_loadedData)) {
		return $this->_loadedData;
	}
	$items = $this->collection->getItems();
	
	foreach ($items as $brand) {			
		$brandData = $brand->getData();
		$brand_img = $brandData['brand_image'];
		$brand_img_url = $brandData['brand_image_url'];
		unset($brandData['brand_image']);
		unset($brandData['brand_image_url']);
		$brandData['brand_image'][0]['name'] = $brand_img;
		$brandData['brand_image'][0]['url'] = $brand_img_url;			
		$this->_loadedData[$brand->getEntityId()] = $brandData;		
	}
	
	return $this->_loadedData;
}