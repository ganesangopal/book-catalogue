<?php

namespace Drupal\custom_book\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\Core\Link;

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
        $link = Link::fromTextAndUrl($this->t('My favourite catalogues'), Url::fromUri('internal:/user/' . $uid . '/my-favourite-catalogues'))->toString();
        drupal_set_message($this->t('Book is added to @link successfully.', ['@link' => $link]), 'status', TRUE);
      }
      else {
      	$link = Link::fromTextAndUrl($this->t('My favourite lists'), Url::fromUri('internal:/user/' . $uid . '/my-favourite-catalogues'))->toString();
      	drupal_set_message($this->t('Book is already available in @link.', ['@link' => $link]), 'status', TRUE);
      }
  	}
    $url = Url::fromUri('internal:/home');
    $response = new RedirectResponse($url->toString());
    $response->send();
  }

}
