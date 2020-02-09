<?php
/**
 * Created by PhpStorm.
 * User: podsh
 * Date: 22.08.2018
 * Time: 21:39
 */

namespace PodshVitaly;

class Dump
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
            self::getStyle();
        }
        return self::$instance;
    }

    public function getDump($array)
    {
        echo '<div class="pvdump__item">';
        self::createList($array);
        echo '</div>';
    }

    protected function createList($array)
    {
        echo '<ul class="varDump">';
        if (is_object($array)) {
            echo '<li class="array" name="object"><var>(' . get_class($array) . ')</var> => <span>open</span> ';
            echo '<ul class="varDump">';

            try {
                foreach ($array as $key => $el) {
                    echo '<li class="array" name="' . $key . '"><var>('
                        . gettype($el)
                        . ') '
                        . $key
                        . '</var> => <span>open</span> ';
                    echo '<ul class="varDump">';
                    echo '<pre>';
                    var_dump($el);
                    echo '</pre>';
                    echo '</ul>';
                    echo ' </li>';
                }
            } catch (\Exception $e) {
                echo '<pre>';
                var_dump($array);
                echo '</pre>';
            }

            echo '</ul>';
            echo ' </li>';
        } else {
            foreach ($array as $key => $el) {
                if (is_array($el) || is_object($el)) {
                    echo '<li class="array" name="' . $key . '"><var>(' . gettype($el) . ') ' . $key . '</var> => <span>open</span> ';
                    self::createList($el);
                    echo ' </li>';
                } else {
                    echo '<li name="' . $key . '"><var>(' . gettype($el) . ') ' . $key . '</var> => value: "' . $el . '"</li>';
                }

            }
        }


        echo '</ul>';

    }

    static protected function getStyle()
    {
        echo "
          <style>   
          #pvDump{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 99999998;            
            background: #0A121C;
            color: white;
            max-height: 100%;
            overflow-y: scroll;           
          }   
          #pvDump input{
          width: 100%;
          }
          
          #pvDump__close{
          position: fixed;
             z-index: 99999999;            
             top: 0;  
             right: 18px;
          }
        #pvDump .varDump{
            display: block;
            
        }
        #pvDump .varDump li{
        line-height: 120%;
        }      
        #pvDump .varDump .array:hover {
            cursor: pointer;
        }
        #pvDump .varDump .array span{
            color: #ff5b1d;
        }
        #pvDump .varDump .varDump{
            display: none;
            padding: 5px 0 10px 30px;

        }
        #pvDump .varDump li.active>.varDump{
            display: block;
        }
    </style>
    <script>
        window.onload = function(){   
            var body =  document.querySelector('body');
            var node = document.createElement('div');
             node.style.display = 'none';
            var input = document.createElement('input');
            var buttonClose = document.createElement('button');
            node.setAttribute('id','pvDump');            
            node.prepend(input);
            
            body.prepend(node);
            buttonClose.setAttribute('id','pvDump__close');buttonClose.innerHTML = 'dump';  
            buttonClose.onclick = function(){ 
                if (node.style.display === 'none') {
                    node.style.display = 'block';
                }else{
                    node.style.display = 'none';
                }   
            }
            body.prepend(buttonClose);
            document.querySelectorAll('.pvdump__item').forEach(function(obj) {
               node.append(obj);               
            }) 
            document.querySelectorAll('.varDump li.array span').forEach(function(obj) {
               obj.onclick = function() {
                   var state = this.parentNode.classList.toggle('active');
                    if(state) this.innerHTML = 'close';
                    else this.innerHTML = 'open';
                    
               } 
            }) 
             document.querySelectorAll('.varDump li var').forEach(function(obj) {
                 obj.onclick = function() {                
                 var path = '';                           
                 getAllParents(obj,'li').forEach(function(li) {
                      path = '[\'' + li.getAttribute('name') + '\']' + path ;
                 });                                
                   
                   input.value = path;
                   input.select();
                    document.execCommand('copy');
                   } 
                   
                   function getAllParents(node,selector) {              
                        var parents = [];
                        var parent = node.closest(selector);                                          
                        if(parent){
                            parents.push(parent);                            
                            getNextParent(parent,selector);
                            return parents;
                        } 
                            
                        function getNextParent(node,selector) {
                          var parent = node.parentNode.closest(selector);                     
                          if(parent && parent !== node){      
                               parents.push(parent);                          
                                getNextParent(parent,selector)                            
                          } 
                        }
                   }
             });              
                    
        }
    </script>
         ";
    }
}