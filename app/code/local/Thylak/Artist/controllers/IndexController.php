<?php
/**
 * @category    Thylak
 * @package     Thylak_Artist
 * 
*/

/**
 * 
 * Artist Module
 * 
 * Front End Controller for Artist Module
 * 
 * @author      Buyan <buyan@talktoaprogrammer.com, bnpart47@yahoo.com>
 */
class Thylak_Artist_IndexController extends Mage_Core_Controller_Front_Action
{
/**
 * Load Layout - Login Page - Login.phtml
*/    
    public function indexAction()
    {
        $_SESSION['id'] = "";
 	    $this->loadLayout();     
		$this->renderLayout();
    }
/**
 * Load Layout - Register Page - register.phtml
*/  
	public function createAction()
	{
        $_SESSION['id'] = "";
		$this->loadLayout();
		$this->renderLayout();
		
	}
/**
 * Load Layout - Artwork Page - artwork.phtml
*/   
	public function artworkAction()
	{
		if($_SESSION['id'] != "") {
		$this->loadLayout();
		$this->renderLayout(); 
		} else {
		return $this->_redirect('*/*/index', array('id' => $_SESSION['id'])); 
		}
		
	}
/**
 * Load Layout - Artist Page - artist.phtml
*/     
    public function artistAction()
    {
        if($_SESSION['id'] != "") {
        $this->loadLayout();
        $this->renderLayout(); 
        } else {
        return $this->_redirect('*/*/index', array('id' => $_SESSION['id'])); 
        }
        
    }    
/**
 * Verify Login Details & login into artwork page
 *
*/    
	public function loginPostAction()
	{

	$collection  = Mage::getModel('artist/artist')->getCollection();
	foreach ($collection as $item) {
        if(($item->email == $this->getRequest()->getPost('email'))&&($item->password == $this->getRequest()->getPost('pass')))
		{
		
			$_SESSION['id'] = $item->artist_id;
			Mage::getSingleton('core/session')->addSuccess('LoginSuccess'); 
			return $this->_redirect('*/*/artist/id/'.$item->artist_id); 
		}
    }
			Mage::getSingleton('core/session')->addSuccess('LoginFailed'); 
			return $this->_redirect('*/*/index'); 		
	}
/**
 * Create a New Account
 *
*/	 
	public function postCreateAction()
	{
	$collection  = Mage::getModel('artist/artist')->getCollection();
	foreach ($collection as $item) {
        if($item->email == $this->getRequest()->getPost('email'))
		{
			Mage::getSingleton('core/session')->addError('Artist email already available'); 
			return $this->_redirect('*/*/index'); 
		}
    }
	$artist  = Mage::getModel('artist/artist');
	$postdata = $this->getRequest()->getPost();
	$artist->setData($postdata);
    $artist->setCreatedTime(now()); 
    $artist->setUpdateTime(now());  
	$artist->save();  
    $_SESSION['id'] = $artist->getData('artist_id') ;
	Mage::getSingleton('core/session')->addSuccess('Artist information saved successfully'); 
	return $this->_redirect('*/*/artist', array('id' => $artist->getData('artist_id')));
	}
/**
 * Save Artwork Details 
 *
*/	
	public function artworksaveAction()
	{
	$imagename = $_FILES['imagename']['name'];
	$artwork  = Mage::getModel('artist/artwork');
	$title = $this->getRequest()->getPost('title');
	$artistid = $this->getRequest()->getPost('artist_id');
	$artwork->setImagename($imagename);
	$artwork->setArtistId($artistid);
	$artwork->setTitle($title);
	$artwork->artistid = $artistid;
	$artwork->imagename = $imagename;
	$uploadartworkimage = $artwork->uploadArtwork();
    $artwork->setCreatedTime(now());
    $artwork->setUpdateTime(now());  
	$artwork->save();  
	Mage::getSingleton('core/session')->addSuccess('Artwork image uploaded successfully'); 
	 return $this->_redirect('*/*/artwork', array('id' => $artistid));
	} 	

/**
 * Save Artist Details 
 *
*/    
    public function artistsaveAction()
    {
        $id = $_SESSION['id'];
        $artist  = Mage::getModel('artist/artist');
        $artist->setData($this->getRequest()->getPost());
        $artist->setUpdateTime(now());
        $artist->setId($id);
        $artist->save();
        Mage::getSingleton('core/session')->addSuccess('Artist information updated successfully'); 
        return $this->_redirect('*/*/artist', array('id' => $id));
    }    
   
}