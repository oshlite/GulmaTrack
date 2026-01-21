#!/bin/bash
# Test Script for Smart Filtering - Wilayah Publik

echo "🧪 SMART FILTERING TEST SUITE"
echo "=============================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test 1: API Response Structure
echo -e "${YELLOW}Test 1: API Response Structure${NC}"
response=$(curl -s http://localhost:8000/api/wilayah/periods)
if echo "$response" | grep -q "filter_structure"; then
    echo -e "${GREEN}✅ filter_structure tersedia${NC}"
else
    echo -e "${RED}❌ filter_structure TIDAK tersedia${NC}"
fi

if echo "$response" | grep -q "tahun_list"; then
    echo -e "${GREEN}✅ tahun_list tersedia${NC}"
else
    echo -e "${RED}❌ tahun_list TIDAK tersedia${NC}"
fi

if echo "$response" | grep -q "latest_period"; then
    echo -e "${GREEN}✅ latest_period tersedia${NC}"
else
    echo -e "${RED}❌ latest_period TIDAK tersedia${NC}"
fi

echo ""

# Test 2: Wilayah Page JavaScript
echo -e "${YELLOW}Test 2: Wilayah Page JavaScript${NC}"
wilayah_page=$(curl -s http://localhost:8000/wilayah)

if echo "$wilayah_page" | grep -q "availablePeriods.filter_structure"; then
    echo -e "${GREEN}✅ JavaScript menggunakan filter_structure${NC}"
else
    echo -e "${RED}❌ JavaScript TIDAK menggunakan filter_structure${NC}"
fi

if echo "$wilayah_page" | grep -q "getAvailableBulanForTahun"; then
    echo -e "${GREEN}✅ getAvailableBulanForTahun function exists${NC}"
else
    echo -e "${RED}❌ getAvailableBulanForTahun function NOT found${NC}"
fi

if echo "$wilayah_page" | grep -q "getAvailableMingguForTahunBulan"; then
    echo -e "${GREEN}✅ getAvailableMingguForTahunBulan function exists${NC}"
else
    echo -e "${RED}❌ getAvailableMingguForTahunBulan function NOT found${NC}"
fi

if echo "$wilayah_page" | grep -q "updateBulanDropdown"; then
    echo -e "${GREEN}✅ updateBulanDropdown function exists${NC}"
else
    echo -e "${RED}❌ updateBulanDropdown function NOT found${NC}"
fi

if echo "$wilayah_page" | grep -q "updateMingguDropdown"; then
    echo -e "${GREEN}✅ updateMingguDropdown function exists${NC}"
else
    echo -e "${RED}❌ updateMingguDropdown function NOT found${NC}"
fi

echo ""

# Test 3: Parse JSON Response
echo -e "${YELLOW}Test 3: JSON Response Parsing${NC}"
echo "$response" | jq . > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ JSON response valid${NC}"
    
    # Extract data
    tahun_list=$(echo "$response" | jq '.tahun_list')
    filter_structure=$(echo "$response" | jq '.filter_structure')
    
    echo "  Tahun List: $tahun_list"
    echo "  Filter Structure Keys: $(echo "$response" | jq '.filter_structure | keys')"
else
    echo -e "${RED}❌ JSON response INVALID${NC}"
fi

echo ""
echo -e "${GREEN}✅ ALL TESTS COMPLETED${NC}"
echo ""
