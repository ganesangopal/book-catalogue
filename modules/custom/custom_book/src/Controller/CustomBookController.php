<?php

namespace Drupal\custom_book\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Controller routines for custom book routes.
 */
class CustomBookController extends ControllerBase {
  
  /**
   * @param $nid
   *  Nid of the node entity.
   *
   * @return
   *  Redirect Response to previous url.
   */
  public function addBookList($nid) {
  	$uid = \Drupal::currentUser()->id();
  	if ($uid && $nid && is_numeric($nid)) {
  	  $node = Node::load($nid);
  	  $node_inspired_users = $node->get('field_inspired_by')->getValue();
  	  $user_exists = array_search($uid, array_column($node_inspired_users, 'target_id'));
  	  // If user is not yet added, add it. Otherwise say message as already available.
  	  if ($user_exists === FALSE) {
        $node->field_inspired_by[] = ['target_id' => $uid];
        $node->save();
        drupal_set_message(t('Book is added to my favourite lists successfully.'), 'status', TRUE);
      }
      else {
      	drupal_set_message(t('Book is already available in my favourite lists.'), 'status', TRUE);
      }
  	}
    $url = Url::fromUri('internal:/home');
    $response = new RedirectResponse($url->toString());
    $response->send();
  }

}
