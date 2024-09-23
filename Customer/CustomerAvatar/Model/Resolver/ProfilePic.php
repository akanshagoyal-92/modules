<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare( strict_types=1 );

namespace Customer\CustomerAvatar\Model\Resolver;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\InputException;
use Magento\CustomerGraphQl\Model\Customer\UpdateCustomerAccount;
use Magento\Framework\Filesystem;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;

class ProfilePic implements ResolverInterface {

	private $fileSystem;

    /**
     * @var GetCustomer
     */
    private $getCustomer;
    private $updateCustomerAccount;

	public function __construct(
		GetCustomer $getCustomer,
		Filesystem $fileSystem,
		UpdateCustomerAccount $updateCustomerAccount,
	) {
		$this->getCustomer = $getCustomer;
		$this->fileSystem = $fileSystem;
		$this->updateCustomerAccount = $updateCustomerAccount;
	}

	/**
	 * @inheritdoc
	 */
	public function resolve(
		Field $field,
		$context,
		ResolveInfo $info,
		array $value = null,
		array $args = null
	) {

        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }

        $customer = $this->getCustomer->execute($context);

		if ( empty( $args['input'] ) || ! is_array( $args['input'] ) || ! count( $args['input'] ) ) {
			throw new GraphQlInputException( __( 'You must specify your input.' ) );
		}
		if ( empty( $args['input']['name'] ) ) {
			throw new GraphQlInputException( __( 'You must specify your "file name".' ) );
		}

		if ( empty( $args['input']['file_content'] ) ) {
			throw new GraphQlInputException( __( 'You must specify your "file base64 encode".' ) );
		}

		try {
			$mediaPath     = $this->fileSystem->getDirectoryRead( DirectoryList::MEDIA )->getAbsolutePath();
			$originalPath  = 'customer/avatar/';
			$mediaFullPath = $mediaPath . $originalPath;
			if ( ! file_exists( $mediaFullPath ) ) {
				mkdir( $mediaFullPath, 0775, true );
			}

			$arrayReturn = [ 'items' => null ];

			$fileName        = rand() . time() . '_' . $args['input']['name'];
			$base64FileArray = explode( ',', $args['input']['file_content'] );

			$fileContent = base64_decode( $base64FileArray[1] );
			$savedFile   = fopen( $mediaFullPath . $fileName, "wb" );
			fwrite( $savedFile, $fileContent );
			fclose( $savedFile );
			$arrayReturn['items'][] = [
				'name'       => $fileName,
				'full_path'  => $mediaFullPath . $fileName,
				'quote_path' => $originalPath . $fileName,
				'order_path' => $originalPath . $fileName,
				'secret_key' => substr( md5( file_get_contents( $mediaFullPath . $fileName ) ), 0, 20 )
			];

			$update['input']['avatar'] 	=	'/avatar/'.$fileName;

	        $this->updateCustomerAccount->execute(
	            $customer,
	            $update['input'],
	            $context->getExtensionAttributes()->getStore()
	        );

			return $arrayReturn;

		}
		catch ( InputException $e ) {
			throw new GraphQlInputException( __( $e->getMessage() ) );
		}
	}
}