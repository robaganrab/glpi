<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2013 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

include ('../inc/includes.php');


$translation = new DropdownTranslation();
if (isset($_POST['add'])) {
   $translation->add($_POST);
   $translation->generateCompletename($_POST, true);
   
} elseif(isset($_POST['update'])) {
   $translation->update($_POST);
   ///TODO : do it on post_update
   $translation->generateCompletename($_POST, false);

   /// TODO use standard massive action
} elseif(isset($_POST['delete_translation'])) {
   if (isset($_POST['item'])) {
      foreach ($_POST['item'] as $id => $value) {
         if ($value == 1) {
            $translation->delete(array('id' => $id));
         }
      }
   }
}
Html::back();
?>