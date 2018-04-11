<?php
/**
 * Created by PhpStorm.
 * User: stretch
 * Date: 4/10/18
 * Time: 9:19 PM
 */

namespace Plaid;


class MetadataTest extends \CITest {

    public function setUp() {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->data = json_decode(json_encode([
            'link_session_id' => "123-abc",
            'institution' => [
                'name' => "Wells Fargo",
                'institution_id' => "ins_4"
            ],
            'accounts' => [
                [
                    'id' => "QPO8Jo8vdDHMepg41PBwckXm4KdK1yUdmXOwK",
                    'name' => "Plaid Checking",
                    'mask' => "0000",
                    'type' => "depository",
                    'subtype' => "checking"
                ]
            ]
        ]));
    }

    /**
     * @covers Metadata::getLinkSessionId
     */
    public function testGetLinkSessionId() {
        $metadata = new Metadata($this->data);

        $this->assertEquals($this->data->link_session_id, $metadata->getLinkSessionId());
    }

    /**
     * @covers Metadata::loadInstitution
     * @covers Metadata::getInstitution
     */
    public function testGetInstitution() {
        $metadata = new Metadata($this->data);

        $this->assertInstanceOf('\Plaid\Metadata\Institution', $metadata->getInstitution());
    }

    /**
     * @covers Metadata::loadAccounts
     * @covers Metadata::getAccounts
     */
    public function testGetAccounts() {
        $metadata = new Metadata($this->data);

        $this->assertInstanceOf('\Plaid\Account', $metadata->getAccounts()[0]);
    }
}