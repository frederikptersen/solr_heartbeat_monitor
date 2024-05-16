<?php

namespace Drupal\solr_heartbeat_monitor\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Symfony\Component\HttpFoundation\Request;

class SolrController extends ControllerBase {

  public function content(Request $request) {
    $clientIp = $request->getClientIp();

    $allowedIps = ['87.104.250.69'];

    if (!in_array($clientIp, $allowedIps)) {
      return [];
    }

    $solrServer = Settings::get('solr_heartbeat_monitor_server');
    $solrPort = Settings::get('solr_heartbeat_monitor_port');

    $solrStatus = $this->checkSolrStatus($solrServer, $solrPort);

    if ($solrStatus === 'running') {
      if ($this->currentUser()->isAuthenticated() && $this->currentUser()->hasPermission('administer site configuration')) {
        $lsOutput = shell_exec('ls -lart');
        $output = "<pre>SOLR Status: running\n\n$lsOutput</pre>";
      } else {
        $output = "<pre>SOLR Status: running</pre>";
      }
    } else {
      $output = "<pre>SOLR Status: not running</pre>";
    }

    return [
      '#type' => 'markup',
      '#markup' => $output,
    ];
  }

   private function checkSolrStatus($server, $port) {
    return 'running';
  }

}

