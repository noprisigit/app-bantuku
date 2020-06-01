<?php

class Partner_m extends CI_Model {
   public function getDetailShop($partnerUniqueID)
   {
      $query = $this->db->query('SELECT partners.PartnerID, partners.PartnerUniqueID, partners.CompanyName, partners.PartnerName, partners.Address, partners.District, partners.Province, partners.PostalCode, partners.Phone, partners.Email, partners.ShopPicture, partners.ShopThumbnail, COUNT(customerslikesshop.PartnerUniqueID) as Disukai FROM partners LEFT JOIN customerslikesshop ON partners.PartnerUniqueID = customerslikesshop.PartnerUniqueID WHERE partners.PartnerUniqueID = "'.$partnerUniqueID.'"');
      return $query->row_array();
   }
}