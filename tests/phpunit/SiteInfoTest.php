<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../lib/bootstrap.php');

class SiteInfoTest extends TestCase {
    public function testSiteInfo(): void {
        $info = new Gazelle\SiteInfo();

        $this->assertIsString($info->phpinfo(), 'siteinfo-phpinfo');
        $this->assertCount(2, $info->uptime(), 'siteinfo-uptime');

        $this->assertIsArray($info->phinx(), 'siteinfo-phinx');
        $this->assertIsString($info->composerVersion(), 'siteinfo-composer-version');
        $this->assertIsArray($info->composerPackages(), 'siteinfo-composer-packages');
        $this->assertTrue($info->tableExists('users_main'), 'siteinfo-table-exists');
        $this->assertEquals([], $info->tablesWithoutPK(), 'siteinfo-table-no-pk');
        $this->assertEquals([], $info->tablesWithDuplicateForeignKeys(), 'siteinfo-table-dup-foreign-keys');
        $this->assertIsArray($info->tableRowsRead('users_main'), 'siteinfo-table-read');
        $this->assertIsArray($info->indexRowsRead('users_main'), 'siteinfo-index-read');
        $this->assertIsArray($info->tableStats('users_main'), 'siteinfo-table-stats');
    }

    /**
     * git binary not available in the CI container
     * @group no-ci
     */
    public function testGitInfo(): void {
        $info = new Gazelle\SiteInfo();
        $this->assertIsString($info->gitBranch(), 'siteinfo-git-branch');
        $this->assertIsString($info->gitHash(), 'siteinfo-git-hash-local');

        // if the following test fails, you need to push your branch to the remote origin
        $this->assertIsString($info->gitHashRemote(), 'siteinfo-git-hash-remote');
    }
}
