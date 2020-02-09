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

    function getDump($array){
        echo '<div class="dump">';

        echo "<input type='text' style='width:100%'>";
        self::createList($array);


        echo '</div>';
    }

    function createList($array){
        echo '<ul class="varDump">';
        foreach ($array as $key=>$el){
            if(is_array($el)){
                echo '<li class="array" name="'.$key.'"><var>('. gettype($el) .') ' . $key . '</var> => <span>open</span> ';
                self::createList($el);
                echo ' </li>';
            }else{
                echo '<li name="'.$key.'"><var>(' . gettype($el) .') ' . $key . '</var> => value: "' . $el . '"</li>';
            }
        }
        echo '</ul>';

    }

    function getStyle()
    {
        echo "
          <style>   
          .dump{
            position: relative;
            z-index: 99999999;
            background: white;
          }   
        .varDump{
            display: block;
            
        }
        .varDump li{
        line-height: 120%;
        }      
        .varDump .array:hover {
            cursor: pointer;
        }
        .varDump .array span{
            color: #ff5b1d;
        }
        .varDump .varDump{
            display: none;
            padding: 5px 0 10px 30px;

        }
        .varDump li.active>.varDump{
            display: block;
        }
    </style>
    <script>
        window.onload = function(){   
            var node =  document.querySelector('body');
           
            document.querySelectorAll('.dump').forEach(function(obj) {
               node.prepend(obj);               
            }) 
            document.querySelectorAll('.varDump li.array span').forEach(function(obj) {
               obj.onclick = function() {
                    this.parentNode.classList.toggle('active');
               } 
            }) 
             document.querySelectorAll('.varDump li var').forEach(function(obj) {
                 obj.onclick = function() {                
                 var path = '';                           
                 getAllParents(obj,'li').forEach(function(li) {
                      path = '[\'' + li.getAttribute('name') + '\']' + path ;
                 });
              
                   var input = obj.closest('.dump').querySelector('input');
                   
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
                          console.log(parent)
                          console.log(node)
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