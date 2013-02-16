<?php

/************************
 * The secret of the streams
 * ----------------
 * Streams are an attempt to create loose coupling amount elements in a view or page.
 * 
 * Streams can be producers by producer_nodes and consumed by consumer_nodes.
 * 
 * It's assumed that a consumer is always on the same level, or lower as the producer of the steam
 * it's reading from.  This means that all elements should be built at the same time, and thus registered
 * at the same time.  It can then be assumed that all producers the consumers needs to worry about will
 * be at least registered when process is called.
 * 
 * A coordinator will have references to producers and consumers, but it will also have access to the static 
 * stream content.
 */
 
namespace Snap\Lib\Node\Extension;

use \Snap\Node\Core\Snapable;
use \Snap\Node\Core\Producer;
use \Snap\Node\Core\Consumer;

class Streams 
	implements \Snap\Lib\Node\Extension {
		
	static protected 
		$streamer = null, 
		$consumers = array(), 
		$producers = array();
	
	/**************
	 * Static hook for backward compatibility with how I used to do things.  Just returns a new instance of node_extension_streams
	 */
	static public function getInstance(){
		$class = get_called_class();
		
		return new $class();
	}
	
	/**************
	 * Initialize the node_extension_streams.  A static member will be populated by the first instance so all others
	 * can share it.
	 */
	public function __construct(){
		if ( self::$streamer == null ){
			self::$streamer = new \Snap\Lib\Streams\Streamer();
		}
	}
	
	/**************
	 * An external hook allowing for other elements to set stream data, not just producers.  This may be taken
	 * away in the future as I'd prefer only producer_node elements produced content
	 */
	public function setStreamData( $stream, $value ){
		self::$streamer->setStreamData( $stream, $value );
	}
	
	public function clear(){
		foreach( self::$consumers as $consumer ){
			$this->removeNode( $consumer );
		}
		
		foreach( self::$producers as $producer ){
			$this->removeNode( $producer );
		}
	}
	
	/**************
	 * Allow for the registeration of producer nodes for the system
	 */
	public function addNode( Snapable $node ){
		if ( $node instanceof Consumer ){
			$this->registerConsumer($node);
		}
		
		if ( $node instanceof Producer ){
			$this->registerProducer($node);
		}
	}
	
	/**************
	 * Allow for the registeration of producer nodes for the system
	 */
	protected function registerProducer( Producer $node ){
		self::$producers[] = $node;
		self::$streamer->register( $node );
	}
	
	/**************
	 * Register a local consumer node
	 */
	protected function registerConsumer( Consumer $node ){
		self::$consumers[] = $node;
	}
	
	/**************
	 * Remove something from the stacks
	 */
	public function removeNode( Snapable $node ){
		if ( $node instanceof Consumer ){
			$this->unregisterConsumer($node);
		}
		
		if ( $node instanceof Producer ){
			$this->unregisterProducer($node);
		}
	}
	
	protected function unregisterProducer( Producer $node ){
		$dex = array_search($node, self::$producers);
		if ( $dex !== false ){
			array_splice(self::$producers, $dex, 1);
		}
		
		self::$streamer->unregister( $node );
	}
	
	protected function unregisterConsumer( Consumer $node ){
		$dex = array_search($node, self::$consumers);
		if ( $dex !== false ){
			array_splice(self::$consumers, $dex, 1);
		}
	}
	
	/**************
	 * Run the stream coordinator's process cycle, which will first have all the producers produce their content, and then 
	 * issue the request for the consumers to do so as well
	 */
	public function run(){
		while( !(empty(self::$producers)&&empty(self::$consumers)) ){
			if ( !empty(self::$producers) ){
				$node = array_shift(self::$producers);
				self::$streamer->produceNode( $node );
			}else{
				$node = array_shift(self::$consumers);
				self::$streamer->consumeNode( $node );
			}
		}
	}
	
	/**************
	 * sets up functionality for loading and saving the streamer for serialization
	 */
	static public function save(){
		if ( self::$streamer != null ){
			$snap_session = new snap_session('_stream');
			
			$snap_session->setVar( 'ether', serialize( self::$streamer ) );
			self::$streamer = null;
		}
	}

	static public function load(){
		if ( self::$streamer == null ){
			$snap_session = new snap_session('_stream');
			
			self::$streamer = unserialize( $snap_session->getVar('ether') );
		}
	}
}